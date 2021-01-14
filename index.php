<?php
/**
 * Пространство имен WheaterOnCity
 * объединяет классы и функции, созданные
 * специально для этого проекта
 */
namespace Weather\WeatherOnCity;

require 'autoload.php';

$connect = New DatabaseConnect();
$cities = New QueryCityName("Moscow", $connect);
$citiesArray = $cities->responseArray();
$connect->close_connect();
$wheaterCitiesArray = array();

foreach ($citiesArray as $city) {
  $wheater = New WeatherRequestCity($city["id"]);
  $wheaterCitiesArray[] = $wheater->dataResponse;
}

echo '<pre>';
var_dump($wheaterCitiesArray);
echo '</pre>';

?>
