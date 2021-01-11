<?php

namespace Weather\WeatherOnCity;

class DatabaseConnect {
  private $connect, $dbHost, $dbAccount, $dbPassw, $dbName;

  function __construct($dbHost = "mariadb", $dbAccount = "quickresto", $dbPassw = "quickresto", $dbName = "quickresto") {
    $this->dbHost = $dbHost;
    $this->dbAccount = $dbAccount;
    $this->dbPassw = $dbPassw;
    $this->dbName = $dbName;
    $this->connect = new \mysqli($this->dbHost, $this->dbAccount, $this->dbPassw, $this->dbName);
    /**
     * Условие, которое одновренменно делает и проверяет соединение с БД
     * и выдает сообщение о его наличии/отсутствии
     * @var object
     */
    if ($this->connect->connect_errno) {
      echo "Error: ". $this->connect->connect_errno;
    } else {
      echo "Status: ". $this->connect->host_info . " !!!YES!!! <br/>";
    }
  }

  public function open_connect() {
    return $this->connect;
  }
  public function close_connect() {
    return $this->connect->close();
  }
}
