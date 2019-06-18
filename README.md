# mizu-voip-sms-php

<h4>Install with composer</h4>

```terminal
composer requre buibr/mizu-voip-sms-php "^2.0"
```

<h4>Usage:</h4>

We have changed the way we submit the authentication data on this library. Dynamicaly the api settings can be set in 2 ways now:
Example 1: create new api object with mizuApi settings.
```php
$api = new \buibr\Mizu\mizuApi( [
    'server' => 'the sip server of yours',
    'authkey'=> 'xxx',
    'authid'=>'xxx',
    'authpwd'=>'xxx',
]);

$mizu = new \buibr\Mizu\Entries\RatingEntry( $api );
$mizu->run();

```

Example 2: pass data directely to entry with all params
```php

$api = new \buibr\Mizu\Entries\RatingEntry([
    'server' => 'the sip server of yours',
    'authkey'=> 'xxx',
    'authid'=>'xxx',
    'authpwd'=>'xxx',
    'bnum'=>'+389xxxxxx'
]);

```



Example 1: Send SMS
```php

$mizu = new \buibr\Mizu\Entries\SmsEntry( $api );
$mizu->setSender("Test"); // sender name
$mizu->setRecipient('+38971xxxxxx'); //  add recepient
$mizu->setMessage('Testing the provider'); //  add message

//  Response
$send = $mizu->run();

```

Example 2: Get Rating to number.
```php

$mizu = new \buibr\Mizu\Entries\RatingEntry( $api );
$mizu->setRecipient('+38971xxxxxx'); //  add recepient

//  Response
$send = $mizu->run();

```

Example 3: Balance of the account
```php

$mizu = new \buibr\Mizu\Entries\BalanceEntry( $api );

//  Response
$send = $mizu->run();

```

<h4>Response:</h4> 

```php

//  Response Minimal
$send = $mizu->minimal()->run();

buibr\Mizu\Response\XmlResponse Object
(
    [code] => 200
    [type] => text/xml
    [time] => 0.376011
    [status] => 1
    [response] => stdClass Object
        (
            [price] => 0.2
            [currency] => EUR
            [destination] => Macedonia-cellular
        )

)


//  Response FULL
$send = $mizu->full()->run();

buibr\Mizu\Response\XmlResponse Object
(
    [code] => 200
    [type] => text/xml
    [time] => 0.372968
    [status] => 1
    [response] => stdClass Object
        (
            [price] => 0.2
            [currency] => EUR
            [destination] => Macedonia-cellular
        )

    [length] => 179
    [request] => GuzzleHttp\Psr7\Response Object
        (
            ...
        )
    [stats] => GuzzleHttp\TransferStats Object
        (
            ...
        )
)

```

<h4>Error:</h4> 
```php

buibr\Mizu\Response\XmlResponse Object
(
    [code] => 200
    [type] => text/xml
    [time] => 0.371516
    [status] => 
    [response] => Array
        (
            [type] => auth failed
            [message] =>  wrong user or password
        )
)

```

