<?php

namespace Weather\WeatherOnCity;

class WeatherRequestCity
{
  private $apiKey, $cityId;

  function __construct($cityId) {
    $this->apiKey = "youropenweatherapikey";
    $this->cityId = $cityId;
  }

  private function crequest($apiUrl)
  {
    $crequest = curl_init($apiUrl);

    curl_setopt($crequest, CURLOPT_HEADER, 0);
    curl_setopt($crequest, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($crequest, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($crequest, CURLOPT_VERBOSE, 0);
    curl_setopt($crequest, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($crequest);
    curl_close($crequest);

    return $response;
  }

  private function setApiUrl() {
    return "http://api.openweathermap.org/data/2.5/weather?id=" . $this->cityId . "&lang=ru&units=metric&APPID=" . $this->apiKey;;
  }

  private function responseDecode($response)
  {
    $dataResponse = (array) \json_decode($response);
    return $dataResponse;
  }

  public function __get($property)
  {
    switch ($property) {
      case 'dataResponse':
          return $this->responseDecode($this->crequest($this->setApiUrl()));
          break;
    }
  }
}
?>
