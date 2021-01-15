<?php
namespace Weather\WeatherOnCity;

use PHPUnit\Framework\TestCase;


/**
 * Так как образ php в docker
 * имеет другие значения массива $_SERVER
 * то лучше в начале каждого тестового файла установить
 * автозагрузчик классов с $_SERVER['PWD']
 * после этого не надо указывать опцию --bootstrap в командной строке
 * помогает, когда для работы теста нужно загрузить другие классы,
 * которые в тесте не участвуют
 */

spl_autoload_register(function ($class_name) {
    require $_SERVER['PWD'].'/classes' . '/' . str_replace("\\", "/", $class_name) . '.php';
});

class QueryCityNameTest0 extends TestCase
{

  public function testCityName()
  {
    $cityName = "Moscow";
    $this->assertNotEmpty($cityName, $message = $cityName);
    return $cityName;
  }

  /**
   * @uses \Weather\WeatherOnCity\DatabaseConnect
   */
  public function testDBConnect()
  {
    $connect = New DatabaseConnect("mariadb", "quickresto", "quickresto", "quickresto");
    $this->assertNotFalse(\is_object($connect));
    return $connect;
  }

  /**
   * @depends testCityName
   * @depends testDBConnect
   */
  public function testQueryCityName($cityName, $connect)
  {
    $cities = New QueryCityName($cityName, $connect);
    $citiesArr = $cities->responseArray();
    $this->assertNotFalse(($citiesArr[0]["name"] == $cityName));
  }

}
?>
