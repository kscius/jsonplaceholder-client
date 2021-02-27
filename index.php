<?php
require_once('jsonplaceholder.php');
$JSONph = new \MSRENA\JSONPlaceHolder();
echo '<pre>';
print_r($JSONph->Posts()->SetQueryParameters([
    '_start' => 0,
    '_end' => 10
])->Call());
?>