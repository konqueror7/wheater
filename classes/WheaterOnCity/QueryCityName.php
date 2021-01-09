<?php
namespace WheaterOnCity;

class QueryCityName
// class QueryCityName extends DatabaseQuery
{
  public $city, $dbResponseArray;
  private $connection, $sql;

  // //private $mysqli;
  function __construct($city, $connection) {
  // function __construct($city, $dbHost, $dbAccount, $dbPassw, $dbName) {
    // parent::__construct($dbHost, $dbAccount, $dbPassw, $dbName);
    $this->city = $this->aposReplace($city);
    $this->connection = $connection->main();
    $this->sql = $this->queryToDataBase();
    $this->dbResponseArray = array();
    echo $this->city.'<br/>';
    echo $this->sql.'<br/>';
    // echo $this->connection->host_info.'<br/>';
    // echo 'Class '. __CLASS__ .' load!';
  }

  private function aposReplace($cityName)
  {
    return str_replace("'", "u0027", $cityName);
  }

  private function aposRemont($cityName)
  {
    return str_replace("u0027", "'", $cityName);
  }

  private function queryToDataBase()
  {
    return "SELECT * FROM `cities` WHERE (`names_virtual` = '".$this->city."' AND `country_virtual` = 'RU')";
  }

  public function responseArray()
  {
    if ($result = $this->connection->query($this->sql)) {
      // Предусловие проверки наличия в $result объекта результата
      // запроса после извлечения функцией fetch_object()
      while ($obj = $result->fetch_object()) {
        // printf ("%s (%s) <br/>", $obj->id, $obj->city);
        // echo '<pre>';
        // var_dump($obj->city);
        // echo '</pre>';
        // Обратная замена на апостроф
        $obj->city = $this->aposRemont($obj->city);
        // Декодирование содержимого json-поля city и помещение в массив
        $this->dbResponseArray[] = json_decode($obj->city, true);
      }
      $result->close();
    } else {
      echo "Не удалось выполнить запрос: (" . $this->connection->errno . ") " . $this->connection->error;
      $result->close();
    }
    $this->connection->close();
    return $this->dbResponseArray;
  }


}
