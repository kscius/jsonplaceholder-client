<?php
require_once('jsonplaceholder.php');
$JSONph = new \MSRENA\JSONPlaceHolder();

echo '<pre>';
var_dump($JSONph->Posts()->Page(2)->Limit(7)->Call());

var_dump($JSONph->Posts()->SetQueryParameters([
    '_start' => 0,
    '_end' => 10
])->Call());
?>