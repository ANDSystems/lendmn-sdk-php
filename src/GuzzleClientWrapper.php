<?php

namespace AndSystems\Lendmn;

use AndSystems\Lendmn\Exceptions\JsonException;
use AndSystems\Lendmn\Exceptions\LendmnException;
use AndSystems\Lendmn\Exceptions\LogicException;
use AndSystems\Lendmn\Exceptions\ValidationException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 * Wrapper class for Guzzle Client
 * class hides validation checks from main Client
 */
class GuzzleClientWrapper
{
    protected $client;

    public function __construct(array $config = [], LoggerInterface $logger = null)
    {
        if ($logger) {
            $config['handler'] = $this->createHandlerStack($logger);
        }
        $this->client = new Client($config);
    }

    protected function createHandlerStack(LoggerInterface $logger)
    {
        $stack = HandlerStack::create();

        return $this->createLoggingHandlerStack($stack, $logger);
    }

    protected function createLoggingHandlerStack(HandlerStack $stack, LoggerInterface $logger)
    {
        $messageFormats = [
            '{method} {uri} HTTP/{version}',
            'HEADERS: {req_headers}',
            'BODY: {req_body}',
            'RESPONSE: {code} - {res_body}',
        ];
        foreach ($messageFormats as $messageFormat) {
            // We'll use unshift instead of push, to add the middleware to the bottom of the stack, not the top
            $stack->unshift(
                Middleware::log(
                    $logger,
                    new MessageFormatter($messageFormat),
                    LogLevel::DEBUG
                )
            );
        }

        return $stack;
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function request(string $method, $path = '', array $options = [])
    {
        try {
            $res = $this->client->request($method, $path, $options);
        } catch (GuzzleException $ex) {
            throw new LogicException('Unable to access LendMN API ['.$path.']', 1, $ex);
        }

        $resp = json_decode($res->getBody(), true);
        $json_err = json_last_error();
        if (JSON_ERROR_NONE !== $json_err) {
            throw new JsonException('Invalid response from LendMN, expecting json ['.$path.']', $json_err);
        }

        if (!isset($resp['code'])) {
            throw new ValidationException('Malformed response, missing \'code\' ['.$path.']', ValidationException::MALFORMED_RESPONSE_MISSING_PARAM);
        }

        if (!isset($resp['response'])) {
            throw new ValidationException('Malformed response, missing \'response\' ['.$path.']', ValidationException::MALFORMED_RESPONSE_MISSING_PARAM);
        }

        if ($resp['code'] > 0) {
            if (isset($resp['response']['error_description'])) {
                throw new LendmnException($resp['response']['error_description'], $resp['code']);
            } elseif (isset($resp['response']['error'])) {
                throw new LendmnException($resp['response']['error'], $resp['code']);
            } elseif (is_string($resp['response'])) {
                throw new LendmnException($resp['response'].' ['.$path.']', $resp['code']);
            } else {
                throw new LendmnException('Unknown API error ['.$path.']', $resp['code']);
            }
        }

        return $resp['response'];
    }
}
