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

class DatabaseConnectTest extends TestCase
{

  public function testDBconn()
  {
    $c = New DatabaseConnect("mariadb", "quickresto", "quickresto", "quickresto");
    $this->assertNotFalse(\is_object($c));
  }
}

?>
