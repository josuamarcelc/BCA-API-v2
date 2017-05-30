# BCA-API-v2
Bank BCA API v2 Generate Signature, Generate Token with PHP to get Statements and Forex

# How To Use

```php

$BCA = new BCA_API();
$payload = array(
        'account_number' => 'E-RATE',
        'symbol_currency' => 'AUD'
    );
echo $BCA->getForex($payload);
$payload = array(
        'corporate_id' => 'BCAAPI2016',
        'account_number' => '0201245680',
        'start_date' => '2016-09-01',
        'end_date' => '2016-09-01'
    );
echo $BCA->getStatements($payload);
```
