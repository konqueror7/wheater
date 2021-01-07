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

// $queryname = "Moscow";
$queryname = "Malaya Gat'";
// Проверка строки поиска на наличие апострофа и замена его на 'u0027'
$queryname = str_replace("'", "u0027", $queryname);
echo $queryname.'<br/>';

/**
 * Строковая переменная для формирования текста
 * множественного запроса к БД
 * @var string
 */
$sql = "SELECT * FROM `cities` WHERE (`names_virtual` = '".$queryname."' AND `country_virtual` = 'RU')";

/**
 * Массив для результатов запроса
 * @var array
 */
$queryarr = array();
/**
 * Условие, которое одновренменно делает и проверяет
 * результат запроса к БД и в ином случае (ошибки)
 * выдает сообщение об ошибке
 * @var object
 */
if ($result = $mysqli->query($sql)) {
  // Предусловие проверки наличия в $result объекта результата
  // запроса после извлечения функцией fetch_object()
  while ($obj = $result->fetch_object()) {
    // printf ("%s (%s) <br/>", $obj->id, $obj->city);
    echo '<pre>';
    var_dump($obj->city);
    echo '</pre>';
    // Обратная замена на апостроф
    $obj->city = str_replace("u0027", "'", $obj->city);
    // Декодирование содержимого json-поля city и помещение в массив
    $queryarr[] = json_decode($obj->city, true);
  }
} else {
  echo "Не удалось выполнить запрос: (" . $mysqli->errno . ") " . $mysqli->error;
}

//echo $decodestring;


echo '<pre>';
var_dump($queryarr);
echo '</pre>';
