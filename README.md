# LendMN Open Platform PHP implementation


## Usage

`LendMNService` бол үндсэн тохиргоогоо хадгалах class, үүнийг хэрэглэгчээс хамааралгүй ашиглаж болно. Харин `Client` нь бол тус хэрэглэгчийн AccessToken-ыг агуулсан class instance. AccessToken тус бүрт нэг Client үүсгэж болно.

### Create and Get AccessToken

```php

namespace Sample;

use LendMN/Api/Client; 
use LendMN/Api/LendMNService;
use LendMN/Api/InvalidResponseException;
use LendMN/Api/ApiException;

use GuzzleHttp\Exception\TransferException;

// ...

$host = "mgw.test.lending.mn";


$service = new LendMNService("128_kCaox7SYU2uUrngjvexN", "EFDLT4j1hK", $host);
try{
  $client = $service->consumeCode("http://localhost/", "XK6FFqg4rNab05d");
} catch(ApiException $ex) {
  //API errors
} catch(InvalidResponseException $ex) {
  //Lendmn non API related errors
} catch(TransferException $ex) {
  //Guzzle errors
}
```
