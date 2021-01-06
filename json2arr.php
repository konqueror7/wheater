<?php

$jsonfile = 'city.list.json';
$jsondata = json_decode(file_get_contents($jsonfile), true);
end($jsondata);
echo '<pre>';
var_dump($jsondata[array_key_last($jsondata)-1]);
//var_dump(end($jsondata));
echo '</pre>';
