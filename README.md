# budget-sms-php

<h4>Install with composer</h4>

```terminal
composer requre buibr/budget-sms-php
```

<h4>Usage:</h4> 

Example 1:
```php
$mizu = new \buibr\Mizu\mizuSMS( [
    'server' => 'the sip server of yours',
    'authKey'=> 'xxx',
    'authId'=>'xxx',
    'authpwd'=>'xxx',
]);

//  sender name
$budget->setSender("Test"); // sender name

//  add recepient
$budget->setRecipient('+38971xxxxxx');

//  add message
$budget->setMessage('Testing the provider');

//  Send the message 
$send = $budget->send();

```

Example 2:
```php
use buibr\Mizu\mizuSMS;

$mizu = new mizuSMS( [
    'server' => 'the sip server of yours',
    'anum'=>'xxx', // sender name
    'authKey'=> 'xxx',
    'authId'=>'xxx',
    'authpwd'=>'xxx',
]);

$credit = $mizu->balance();

```


<h4>Response:</h4> 

Success:

```php
buibr\Mizu\mizuResponse Object
(
    [code] => 200
    [type] => text/plain
    [time] => 0.417484
    [status] => 1
    [response] => $9.8
    [data] => Your credit is $9.8
)
```

Error:


```php
buibr\Mizu\mizuResponse Object
(
    [code] => 200
    [type] => text/plain
    [time] => 0.454436
    [status] => 
    [response] => auth failed: wrong key xxx NORETRY
    [data] => ERROR: auth failed: wrong key xxx NORETRY
)
```