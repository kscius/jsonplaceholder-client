# jsonplaceholder-client
Proof of concept for a Client for [JSON Placeholder](https://github.com/typicode/jsonplaceholder "JSON PlaceHolder")


# Incliuded test

```php
require_once('jsonplaceholder.php');
$JSONph = new \MSRENA\JSONPlaceHolder();

$JSONph->Posts()->SetQueryParameters([
    '_start' => 0,
    '_end' => 10
])->Call();

$JSONph->Posts()->Start()->End(10)->Call();

$JSONph->Posts()->Start(1)->End(10)->Call();

$JSONph->Posts(1)->Embed('comments')->Call();
