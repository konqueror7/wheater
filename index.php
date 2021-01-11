<?php
/**
 * Пространство имен WheaterOnCity
 * объединяет классы и функции, созданные
 * специально для этого проекта
 */
namespace Weather\WeatherOnCity;

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
    include $_SERVER['DOCUMENT_ROOT'] . '/classes' . '/' . str_replace("\\", "/", $class_name) . '.php';
});

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
