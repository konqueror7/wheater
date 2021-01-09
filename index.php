<?php
/**
 * Пространство имен WheaterOnCity
 * объединяет классы и функции, созданные
 * специально для этого проекта
 */
namespace WheaterOnCity;

/**
 * Функция загружает классы из файлов, находящихся в
 * папке с названием, соответсвующим пространству имен
 * в магической константе __NAMESPACE__
 * и  указанной в начале каждого файла класса
 * @var string $class_name - если вывести ее содержимое
 * с помощью echo, то обнаружится что значение переменной
 * состоит из константы __NAMESPACE__ и имени класса
 * в формате "__NAMESPACE__\ИМЯ_КЛАССА"
 * функция str_replace заменяет "\" на "/"
 * и позволяет указать путь до файла при хранении в папках,
 * дублирующих иерархию пространств имен
 */
spl_autoload_register(function ($class_name) {
    //include 'classes'. $class_name . '.php';
    //echo $class_name.'<br/>';
    include $_SERVER['DOCUMENT_ROOT'] . '/classes' . '/' . str_replace("\\", "/", $class_name) . '.php';
});

//include $_SERVER['DOCUMENT_ROOT'] . '/classes/WheaterOnCity' . '/' . 'WheaterInCity.php';
//include $_SERVER['DOCUMENT_ROOT'] . '/classes/WheaterOnCity' . '/' . 'City.php';

$connect = New DatabaseQuery();
// $cities = New QueryCityName("Moscow", $connect);
$cities = New QueryCityName("Malaya Gat'", $connect);
$citiesArray = $cities->responseArray();
echo '<pre>';
var_dump($citiesArray);
echo '</pre>';
// $cities = New QueryCityName("Moscow", "mariadb", "quickresto", "quickresto", "quickresto");
$wheater = New WeatherRequestCity();
// $city = New City();

$apiKey = "5d125367d5c0dd75c25936c5f9d3cfd8";
$cityId = "524901";
$apiUrl = "http://api.openweathermap.org/data/2.5/weather?id=" . $cityId . "&lang=ru&units=metric&APPID=" . $apiKey;

$crequest = curl_init($apiUrl);

curl_setopt($crequest, CURLOPT_HEADER, 0);
curl_setopt($crequest, CURLOPT_RETURNTRANSFER, 1);
//curl_setopt($crequest, CURLOPT_URL, $apiUrl);
curl_setopt($crequest, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($crequest, CURLOPT_VERBOSE, 0);
curl_setopt($crequest, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($crequest);

curl_close($crequest);
$data = json_decode($response);
$currentTime = time();
echo '<pre>';
var_dump($data);
echo '</pre>';
?>
