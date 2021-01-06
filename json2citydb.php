<?php
// Подключение к хосту "mariadb" под учетной записью "quickresto", паролем "quickresto" к БД "quickresto"
$mysqli = new mysqli("mariadb", "quickresto", "quickresto", "quickresto");

/**
* Обявдение строковой переменной
* $jsonfile и присвоение ее значению имени файла,
* содержащего список городов в json-формате
*/
$jsonfile = 'city.list.json';

/**
 * массив для декодированных записей из json-файла
 * @var array
 */
$jsondata = json_decode(file_get_contents($jsonfile), true);
/**
 * Строковая переменная для формирования текста
 * множественного запроса к БД
 * @var string
 */
$sql = '';

/**
 * Цикл для перебора ключей массива $jsondata
 * и внесения их значения $value
 * в каждый оператор INSERT множественного SQL-запроса.
 * Обратное преобразование в json-данные осущетвляется
 * с флагом 'JSON_UNESCAPED_UNICODE',
 * который предотвращает кодирование unicode-символов
 * в виде последовательности символов в формате 'uXXXX'
 * @var string
 */
echo count($jsondata).'<br/>';
$countrecords = 0;
foreach ($jsondata as $value) {
  $sql .= "INSERT INTO cities(city) VALUES ('".json_encode($value, JSON_UNESCAPED_UNICODE)."');";
  $countrecords++;
}
echo $countrecords.'<br/>';
//$sql .= "INSERT INTO cities(city) VALUES ('".json_encode($jsondata[0])."')";


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

/**
 * Условие, которое одновренменно делает и проверяет
 * результат множественного запроса к БД и в случае неудачи
 * выдает сообщение об ошибке
 * @var object
 */
if (!$mysqli->multi_query($sql)) {
    echo "Не удалось выполнить мультизапрос: (" . $mysqli->errno . ") " . $mysqli->error;
}

//$res = $mysqli->query("SELECT * FROM cities");

//if ($res->num_rows > 0) {
  //echo "Table not empty"."<br/>";
//} else {
  //echo "Table empty"."<br/>";
//}
