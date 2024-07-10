[![Current version](https://img.shields.io/packagist/v/maatify/routee)][pkg]
[![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/maatify/routee)][pkg]
[![Monthly Downloads](https://img.shields.io/packagist/dm/maatify/routee)][pkg-stats]
[![Total Downloads](https://img.shields.io/packagist/dt/maatify/routee)][pkg-stats]
[![Stars](https://img.shields.io/packagist/stars/maatify/routee)](https://github.com/maatify/RouteeSms/stargazers)

[pkg]: <https://packagist.org/packages/maatify/routee>
[pkg-stats]: <https://packagist.org/packages/maatify/routee/stats>
# Installation

```shell
composer require maatify/routee
```

# Usage

### Instance
```php
use Maatify\RouteeSms\Routee;

require_once __DIR__ . '/vendor/autoload.php';

$routee = new Routee(__API_KEY__, __SENDER_NAME__); // Routee instance
```

#
### Check Balance
```PHP

$result = $routee->CheckBalance();

print_r($result);
```
#### Response Example :
##### Success Example
>       Array
>       (
>            [currency] => Array
>                (
>                    [code] => USD
>                    [name] => US Dollar
>                    [sign] => $
>                )
>        
>            [balance] => 400.83762
>            [success] => 1
>       )

##### Error Example
>        (
>            [timestamp] => 2024-07-10T03:39:48.787+0000
>            [status] => 401
>            [error] => Unauthorized
>            [message] => Bad credentials
>            [path] => /accounts/me/balance
>            [success] => 1
>        )
#

### Send SMS Message
```PHP

$result = $routee->SendSms(__PHONE_NUMBER__, __SMS_MESSAGE__);

print_r($result);
```
#### Response Example :
##### Success Example
>       Array
>       (
>            [trackingId] => 66fb6987-6be1-4e92-b6d5-ca3ca5227d23
>            [status] => Queued
>            [createdAt] => 2024-07-10T03:32:32.916Z
>            [from] => Maatify.dev
>            [to] => __PHONE_NUMBER__
>            [body] => Welcome to Maatify.dev
>            [bodyAnalysis] => Array
>                (
>                    [parts] => 1
>                    [unicode] => 
>                    [characters] => 22
>                )
>        
>            [flash] => 
>            [callback] => Array
>                (
>                    [url] => {{if your api is set}}
>                    [strategy] => OnChange
>                )
>        
>            [success] => 1
>        )

##### Error Example
>        (
>            [timestamp] => 2024-07-10T03:39:48.787+0000
>            [status] => 401
>            [error] => Unauthorized
>            [message] => Bad credentials
>            [path] => /sms
>            [success] => 1
>        )


### Get your account transactions
```PHP
$time_from = date('Y-m-d\TH:i\Z', strtotime('2023-01-01 00:00'));
$time_to = date('Y-m-d\TH:i\Z', strtotime('2024-07-31 23:59:59'));
$result = $routee->Transactions($time_from, $time_to);

print_r($result);
```
#### Response Example :
##### Success Example
>       Array
>       (
>            [content] => Array
>                (
>                    [0] => Array
>                        (
>                            [id] => 41e667b1-f9f1-4ed4-8ff3-a8032309ff3e
>                            [source] => 517879******7503
>                            [amount] => 35
>                            [status] => Completed
>                            [balanceBefore] => 0.054
>                            [balanceAfter] => 0.054
>                            [date] => 2023-05-27T10:14:26Z
>                            [actions] => Array
>                                (
>                                    [0] => Array
>                                        (
>                                            [id] => e9bc07a1-dea1-4561-a020-52997904e18a
>                                            [type] => Status Changed
>                                            [amount] => 35
>                                            [date] => 2023-05-27T10:15:03Z
>                                            [balanceBefore] => 0.054
>                                            [balanceAfter] => 35.054
>                                            [status] => Completed
>                                        )
>       
>                               )
>       
>                       )
>       
>                   [1] => Array
>                       (
>                           [id] => 0af024fa-8f1a-4ca8-b837-f99001a9a906
>                           [source] => 559444******1710
>                           [amount] => 20
>                           [status] => Completed
>                           [balanceBefore] => 0.088
>                           [balanceAfter] => 0.088
>                           [date] => 2023-03-25T00:22:50Z
>                           [actions] => Array
>                               (
>                                   [0] => Array
>                                       (
>                                           [id] => 70a6098e-7ddd-4496-a105-5e80af4f6883
>                                           [type] => Status Changed
>                                           [amount] => 20
>                                           [date] => 2023-03-25T00:23:04Z
>                                           [balanceBefore] => 0.088
>                                           [balanceAfter] => 20.088
>                                           [status] => Completed
>                                       )
>       
>                               )
>       
>                       )
>       
>               )
>       
>           [last] => 1
>           [totalElements] => 2
>           [totalPages] => 1
>           [numberOfElements] => 2
>           [first] => 1
>           [size] => 20
>           [number] => 0
>           [success] => 1
>       )


### Send SMS Message WIth Callback


### Instance
```php
use Maatify\RouteeSms\Routee;

require_once __DIR__ . '/vendor/autoload.php';

$routee = new Routee(__API_KEY__, __SENDER_NAME__, __YOUR_CALLBACK_URL__); // SmsEG instance
```

#### Send SMS Message WIth Callback On Change
```PHP

$result = $routee->SendSmsWithCallBackOnChange(__PHONE_NUMBER__, __SMS_MESSAGE__);

print_r($result);
```
#### Send SMS Message WIth Callback On Completion(
```PHP

$result = $routee->SendSmsWithCallBackOnCompletion(__PHONE_NUMBER__, __SMS_MESSAGE__);

print_r($result);
```
