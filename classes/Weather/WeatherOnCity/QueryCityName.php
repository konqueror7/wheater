<?php
namespace Weather\WeatherOnCity;

class QueryCityName
{
  public $city, $dbResponseArray;
  private $connection, $sql;

  function __construct($city, $connection) {
    $this->city = $this->aposReplace($city);
    $this->connection = $connection->open_connect();
    $this->sql = $this->queryToDataBase();
    $this->dbResponseArray = Array();
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

  public function responseDataBase()
  {
      return $this->connection->query($this->sql);
  }


  public function responseArray()
  {
    $responseArray = array();
    if ($res = $this->responseDataBase()) {
      while ($obj = $res->fetch_object()) {
        // Обратная замена на апостроф
        $obj->city = $this->aposRemont($obj->city);
        // Декодирование содержимого json-поля city и помещение в массив
        $this->dbResponseArray[] = json_decode($obj->city, true);
      }
      $res->close();
    } else {
      echo "Не удалось выполнить запрос: (" . $this->connection->errno . ") " . $this->connection->error;
      $res->close();
    }
    return $this->dbResponseArray;
  }
}
