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

class QueryCityNameTest extends TestCase
{
  protected $cityName, $connect;

  protected function setUp(): void
  {
      $this->cityName = "Moscow";
      if (\extension_loaded('mysqli')) {
        $this->connect = New DatabaseConnect();
      }
  }

  protected function tearDown(): void
  {
      $this->cityName = "";
      $this->connect->close_connect();
  }

  public function testQueryCityName()
  {
    $cities = New QueryCityName($this->cityName, $this->connect);
    $citiesArr = $cities->responseArray();
    //echo "string";
    $this->assertNotFalse(($citiesArr[0]["name"] == $this->cityName));
  }

}
?>
