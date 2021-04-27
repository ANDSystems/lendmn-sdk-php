# LendMN Open Platform PHP implementation


## Usage

`LendmnClient` бол үндсэн class. https://developers.lend.mn/ дээрх Documentation-ы Server Api-г хэрэгжүүлсэн Client.

### Create and Get AccessToken

```php

namespace Sample;

use AndSystems\Lendmn\Client as LendmnClient;
use AndSystems\Lendmn\Exceptions\LendmnException;
use Psr\Log\LoggerInterface;
// ...

/**
 * @param string $baseUri 'https://b2b.lend.mn' үндсэн LendMN gateway
 * @param string $clientId '1_abcdef123456' таны OAuth client_id
 * @param string $clientSecret 'QWErty123456' таны OAuth client_id
 * @param string $token 'NzI1ZmZjZDdmODdhNW' таны x-and-auth-token
 * @param string|null $redirectUri 'https://sample.domain' developers.lend.mn дээр бүртгэлтэй "Веб нээгдэх хаяг"  буюу OAuth2 redirect_uri
 * @param string|null $publicKey LendMN-ээс ирсэн request-уудыг баталгаажуулах publicKey, ASCII текст эсвэл ascii текст-тэй file-руу заасан absolute path байж болно
 * @param LoggerInterface|null $logger
 */
$client = new LendmnClient($baseUri, $clientId, $clientSecret, $token, $redirectUri, $publicKey, $logger);
try{
  $client->getAccessToken($authorization_code);
} catch(LendmnException $ex) {
  // api алдаа нууд
}
```
