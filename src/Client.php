<?php

namespace AndSystems\Lendmn;

use AndSystems\Lendmn\Exceptions\JsonException;
use AndSystems\Lendmn\Exceptions\LogicException;
use AndSystems\Lendmn\Exceptions\ValidationException;
use AndSystems\Lendmn\Factory\AccessTokenFactory;
use AndSystems\Lendmn\Factory\InvoiceDetailFactory;
use AndSystems\Lendmn\Factory\InvoiceEventFactory;
use AndSystems\Lendmn\Factory\InvoiceFactory;
use AndSystems\Lendmn\Factory\UserFactory;
use AndSystems\Lendmn\Model\AccessToken;
use AndSystems\Lendmn\Model\AccessTokenInterface;
use AndSystems\Lendmn\Model\Invoice;
use AndSystems\Lendmn\Model\User;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;

class Client implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    const PATH_REQUEST_ACCESS_TOKEN = 'oauth/v2/token';
    const PATH_REQUEST_USER_INFO = 'user/info';
    const PATH_REQUEST_USER_INVOICE = 'w/invoices';
    const PATH_REQUEST_INVOICE_DETAIL = 'w/invoices/%s';

    protected $publicKey;
    protected $clientId;
    protected $clientSecret;
    protected $token;
    protected $baseUri;

    /** @var User */
    protected $user;

    /** @var AccessToken */
    protected $accessToken;
    protected $clientWrapper;

    /**
     * @param string          $baseUrl      Server base url (beta: https://b2b.lend.mn )
     * @param string          $publicKey    key used to validate EventHook
     * @param string          $clientId     provied client id
     * @param string          $clientSecret
     * @param string          $token
     * @param LoggerInterface $logger
     */
    public function __construct($baseUri, $clientId, $clientSecret, $token, $redirectUri=null, $publicKey = null, $logger = null)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->token = $token;
        $this->redirectUri = $redirectUri;

        $this->setPublicKey($publicKey);
        $this->setBaseUri($baseUri);

        $this->logger = $logger;
        $this->createGuzzleClient();
    }

    protected function createGuzzleClient()
    {
        $options = ['base_uri' => rtrim($this->baseUri, '/').'/api/'];

        $this->clientWrapper = new GuzzleClientWrapper($options, $this->logger);
    }

    public function setLogger(LoggerInterface $logger = null)
    {
        $this->logger = $logger;
        $this->createGuzzleClient();
    }

    public function setPublicKey($publicKey = null)
    {
        if ($publicKey != null && !is_string($publicKey)) {
            throw new ValidationException('invalid public key, expecting path to key or public key string', ValidationException::INVALID_PUBLIC_KEY);
        }

        if (is_file($publicKey)) {
            $publicKey = file_get_contents($publicKey);
        }

        if ($publicKey && !openssl_pkey_get_public($publicKey)) {
            throw new ValidationException('invalid public key', ValidationException::INVALID_PUBLIC_KEY);
        }
        $this->publicKey = $publicKey;
    }

    public function setBaseUri($baseUri)
    {
        if (!filter_var($baseUri, FILTER_VALIDATE_URL)) {
            throw new ValidationException('invalid uri provided', 1);
        }
        $this->baseUri = $baseUri;
    }

    public function getGuzzleClient()
    {
        return $this->clientWrapper->getClient();
    }

    public function getGuzzleClientWrapper()
    {
        return $this->clientWrapper;
    }

    /**
     * @return AccessToken
     */
    public function getAccessToken($authorization_code)
    {
        $res = $this->clientWrapper->request('POST', self::PATH_REQUEST_ACCESS_TOKEN, [
            'form_params' => [
                'code' => $authorization_code,
                'redirect_uri' => $this->redirectUri,
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'grant_type' => 'authorization_code',
            ],
        ]);

        $this->accessToken = AccessTokenFactory::createAccessTokenFromArray($res);

        return $this->accessToken;
    }

    /**
     * @return User
     */
    public function fetchUserFromToken($credentials)
    {
        $res = $this->clientWrapper->request('GET', self::PATH_REQUEST_USER_INFO, [
            'headers' => [
                'x-and-auth-token' => (string) $credentials,
            ],
        ]);

        return UserFactory::createUserFromArray($res);
    }

    /**
     * @return Invoice
     */
    public function createUserInvoice($amount, $duration, $description, $accessToken = null, $successUri = null)
    {
        if (!$accessToken && !$this->accessToken) {
            throw new ValidationException('Missing parameter \'accessToken\'', ValidationException::MISSING_PARAMETER);
        }

        if (!$accessToken) {
            $accessToken = $this->accessToken->getToken();
        }

        if ($accessToken instanceof AccessTokenInterface) {
            $accessToken = $accessToken->getToken();
        } elseif (!is_string($accessToken)) {
            throw new ValidationException('Invalid parameter \'accessToken\' expecting string or \'AccessTokenInterface\'', ValidationException::INVALID_PARAMETER);
        }

        if (!is_numeric($amount)) {
            throw new ValidationException('Invalid parameter \'amount\' expecting number', ValidationException::INVALID_PARAMETER);
        }
        $amount = (float) $amount;

        if (!is_numeric($duration)) {
            throw new ValidationException('Invalid parameter \'duration\' expecting number', ValidationException::INVALID_PARAMETER);
        }
        $duration = (float) $duration;

        if (null !== $successUri && !filter_var($successUri, FILTER_VALIDATE_URL)) {
            throw new ValidationException('Invalid parameter \'successUri\' expecting URI', ValidationException::INVALID_PARAMETER);
        }

        $res = $this->clientWrapper->request('POST', self::PATH_REQUEST_USER_INVOICE, [
            'headers' => [
                'x-and-auth-token' => (string) $accessToken,
            ],
            'form_params' => [
                'amount' => $amount,
                'duration' => $duration,
                'description' => $description,
                'successUri' => $successUri,
            ],
        ]);

        return InvoiceFactory::createInvoiceFromArray($res);
    }

    /**
     * @return Invoice
     */
    public function createInvoice($amount, $duration, string $description, string $trackingData = null, $phoneNumber = null, $userWalletNumber = null)
    {
        if (!is_string($description)) {
            throw new ValidationException('Invalid parameter \'description\' expecting string', ValidationException::INVALID_PARAMETER);
        }

        if (null !== $trackingData && (!is_string($trackingData) || strlen($trackingData) > 50)) {
            throw new ValidationException('Invalid parameter \'trackingData\' expecting string(50) ', ValidationException::INVALID_PARAMETER);
        }

        if (!is_numeric($amount)) {
            throw new ValidationException('Invalid parameter \'amount\' expecting number', ValidationException::INVALID_PARAMETER);
        }
        $amount = (float) $amount;

        if (!is_numeric($duration)) {
            throw new ValidationException('Invalid parameter \'duration\' expecting number', ValidationException::INVALID_PARAMETER);
        }
        $duration = (float) $duration;

        $res = $this->clientWrapper->request('POST', self::PATH_REQUEST_USER_INVOICE, [
            'headers' => [
                'x-and-auth-token' => (string) $this->token,
            ],
            'form_params' => [
                'amount' => $amount,
                'duration' => $duration,
                'description' => $description,
                'phoneNumber' => $phoneNumber,
                'userWalletNumber' => $userWalletNumber,
                'trackingData' => $trackingData,
            ],
        ]);

        return InvoiceFactory::createInvoiceFromArray($res);
    }

    public function checkInvoice($invoiceNumber, $token = null)
    {
        if ($this->logger) {
            $this->logger->debug('Checking invoice: '.$invoiceNumber);
        }

        if (null === $invoiceNumber) {
            throw new ValidationException('Missing parameter \'invoiceNumber\'', ValidationException::INVALID_PARAMETER);
        }

        if (null === $token && null !== $this->accessToken) {
            $token = $this->accessToken->getToken();
        } elseif (null === $token && null !== $this->token) {
            $token = $this->token;
        } elseif (null === $token) {
            throw new ValidationException('Missing parameter \'token\'', ValidationException::INVALID_PARAMETER);
        } elseif ($token instanceof AccessTokenInterface) {
            $token = $token->getToken();
        }

        $res = $this->clientWrapper->request('GET', sprintf(self::PATH_REQUEST_INVOICE_DETAIL, $invoiceNumber), [
            'headers' => [
                'x-and-auth-token' => (string) $token,
            ],
        ]);

        $invoiceDetail = InvoiceDetailFactory::createInvoiceDetailFromArray($res);
        if ($this->logger) {
            $this->logger->debug('Invoice: '.$invoiceNumber, $invoiceDetail->toArray());
        }

        return $invoiceDetail;
    }

    public function processHook($requestData)
    {
        $data = json_decode($requestData, true);
        $json_err = json_last_error();
        if (JSON_ERROR_NONE !== $json_err) {
            throw new JsonException('Invalid request from Lendmn, expecting json webhook', $json_err);
        }

        $signature = $data['signature'];
        unset($data['signature']);
        $encodedData = json_encode($data, JSON_UNESCAPED_UNICODE);

        $isValid = openssl_verify($encodedData, base64_decode($signature), $this->publicKey, 'sha256WithRSAEncryption');

        if (!$isValid) {
            throw new LogicException('signature invalid', 2);
        }

        return InvoiceEventFactory::createInvoiceEventFromArray($data);
    }
}
