# BCA-API-v2
Bank BCA API v2 Generate Signature, Generate Token with PHP to get Statements and Forex

# How To Use

```php

$BCA = new BCA_API();
$payload = array(
        'account_number' => 'E-RATE',
        'symbol_currency' => 'USD'
    );
echo $BCA->getForex($payload);
$payload = array(
        'corporate_id' => 'BCAXXXXXXX',
        'account_number' => 'XXXXXXXXXX',
        'start_date' => '2024-09-01',
        'end_date' => '2024-09-30'
    );
echo $BCA->getStatements($payload);
```

[![josuamarcelc](http://stackexchange.com/users/flair/1702393.png)](http://stackexchange.com/users/1702393)
