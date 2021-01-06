<?php
// Подключение к хосту "mariadb" под учетной записью "quickresto", паролем "quickresto" к БД "quickresto"
$mysqli = new mysqli("mariadb", "quickresto", "quickresto", "quickresto");

/**
 * Условие, которое одновренменно делает и проверяет соединение с БД
 * и выдает сообщение о его наличии/отсутствии
 * @var object
 */
if ($mysqli->connect_errno) {
  echo "Error: ". $mysqli->connect_errno;
} else {
  echo "Status: ". $mysqli->host_info . "<br/>";
}
