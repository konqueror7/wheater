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
 * Количество записей в массиве
 * @var integer
 */
$countrecords = count($jsondata);

echo $countrecords.'<br/>';

/**
 * Строковая переменная для формирования текста
 * множественного запроса к БД
 * @var string
 */
$sql = '';


$currentrecord = 0;

$i = 0;

$recordingbatch = 3;

$endrecord = count($jsondata);

/**
 * Цикл с постусловием для перебора ключей массива $jsondata
 * "пакетом" из нескольких записей, число которых указано в переменной $recordingbatch
 * Их значения вносятся в оператор INSERT множественного SQL-запроса.
 * Обратное преобразование в json-данные осущетвляется
 * с флагом 'JSON_UNESCAPED_UNICODE',
 * который предотвращает кодирование unicode-символов
 * в виде последовательности символов в формате 'uXXXX'
 * @var string
 */

do {
  $sql = '';
  for ($i = $currentrecord; $i < $currentrecord + $recordingbatch; $i++) {
    $item = $jsondata[$i];
    // Использование функций для замены или экранирования апострофа "'"
    // осложнено json_encode и опциями JSON_UNESCAPED_UNICODE, JSON_HEX_APOS
    // потому что не все названия, содержащие их, импортируются в запись таблицы

    // addslashes() экранирует его слэшем но при json_encode с опцией JSON_UNESCAPED_UNICODE слэш убирается
    // $item["name"] = addslashes($item["name"])

    // htmlspecialchars() никак не влияет на апостроф
    //$item["name"] = htmlspecialchars($item["name"]);
    if (strstr($item["name"], "'")) {
      echo $item["id"].' - '.$item["name"].'<br/>';
    }
    // json_encode с опциями JSON_UNESCAPED_UNICODE, JSON_HEX_APOS
    // производит замену апострофа на 'u0027' при этом u без слэша
    // Возможна замена апострофа на перевернутую кавычку "’"
    // $item["name"] = str_replace("'", "’", $item["name"]);
    // лучше использовать json_encode с указаными опциями JSON_UNESCAPED_UNICODE, JSON_HEX_APOS
    // в запросах на вставку и на поиск
    $sql .= "INSERT INTO cities(city) VALUES ('".json_encode($item, JSON_UNESCAPED_UNICODE | JSON_HEX_APOS)."');";
    //$sql .= "INSERT INTO cities(city) VALUES ('".json_encode($item)."');";
  }

  /**
   * Условие, которое одновренменно делает и проверяет
   * результат множественного запроса к БД и в случае неудачи
   * выдает сообщение об ошибке
   * в ином случае происходит сброс буфера результатов
   * https://habr.com/ru/post/21326/
   * https://www.php.net/manual/ru/mysqli.next-result.php
   * https://www.php.net/manual/ru/mysqli.store-result.php
   *
   * @var object
   */
  if (!$mysqli->multi_query($sql)) {
      echo "Не удалось выполнить мультизапрос: (" . $mysqli->errno . ") " . $mysqli->error;
  } else {
    while($mysqli->next_result()) $mysqli->store_result();
  }



  // echo '<pre>';
  // echo $sql.'<br/>';
  // echo $i.'<br/>';
  // echo '</pre>';
  $currentrecord = $i;
  if ($endrecord - $currentrecord < $recordingbatch) {
    $recordingbatch = $endrecord - $currentrecord;
  }
  //$mysqli->store_result();
} while ($currentrecord < $endrecord);


// foreach ($jsondata as $value) {
//   $sql .= "INSERT INTO cities(city) VALUES ('".json_encode($value, JSON_UNESCAPED_UNICODE)."');";
// }
