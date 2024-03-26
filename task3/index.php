<?php

header('Content-Type: text/html; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  if (!empty($_GET['save'])) {
    print('
      <p>
        Спасибо, результаты сохранены.
      </p>
      ');
  }
  include('forma.php');
  exit();
}

$name = $_POST['name'];
$email = $_POST['email'];
$phone= $_POST['tel'];
$year = $_POST['year'];
$month=$_POST['month'];
$day=$_POST['day'];
$sex = $_POST['sex'];
$biography = $_POST['biography'];
$checkboxContract = isset($_POST['checkboxContract']);
// if(isset($_POST["lang[]"])) {
    $lang = $_POST["lang[]"];
  //   $filtred_lang = array_filter($lang,
  //   function($value) {
  //       return($value == 'Python' || $value == 'JS' || $value == 'C++'||$value =='PHP'||$value =='Java');
  //   }
  // );
// }
$errors = FALSE;

if (empty($name)) {
  print('
    <h1>
      Заполните имя.
    </h1>
  <br/>');
  $errors = TRUE;
}

if (empty($email)) {
  print('
    <h1>
      Заполните email.
    </h1>
  <br/>');
  $errors = TRUE;
} else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  print('
    <h1>
      Корректно* заполните email.
    </h1>
  <br/>');
  $errors = TRUE;
}
function validating($phone){
    if(!preg_match('/^(8|\+7)[-\(]?\d{3}\)?-?\d{3}-?\d{2}-?\d{2}$/', $phone)) {
    // print(' Valid Phone Number');
    // } else {
    print('Invalid Phone Number!');
    }
    }
if (empty($phone)) {
    print('
      <h3>
        Заполните телефон.
      </h3>');
    $errors = TRUE;
  } else validating($phone);

if (!is_numeric($year)) {
  print('
    <h1>
      Неправильный формат ввода года.
    </h1>
  <br/>');
  $errors = TRUE;
} else if ((2023 - $year) < 14) {
  print('
    <h1>
      Извините, вам должно быть 14 лет.
    </h1>
  <br/>');
  $errors = TRUE;
}

if ($sex != 'male' && $sex != 'female') {
  print('
    <h1>
      Выбран неизвестный пол.
    </h1>
  <br/>');
  $errors = TRUE;
}

if (empty($lang)) {
    print('
      <h1>
        Не выбран  любимый язык программирования.
      </h1>');
    $errors = TRUE;
  }


if (empty($biography)) {
  print('
    <h1>
      Расскажи о себе что-нибудь.
    </h1>
  <br/>');
  $errors = TRUE;
} else if (!preg_match('/^[\p{Cyrillic}\d\s,.!?-]+$/u', $biography)) {
  print('
    <h1>
      Недопустимый формат ввода биографии.
    </h1>
  <br/>');
  $errors = TRUE;
}

if ($checkboxContract == '') {
  print('
    <h1>
      Ознакомьтесь с контрактом.
    </h1>
  <br/>');
  $errors = TRUE;
}


if ($errors) {
  exit();
}

$user = 'u59174';
$pass = '4061054';
$db = new PDO('mysql:host=localhost;dbname=u59174', $user, $pass, array(PDO::ATTR_PERSISTENT => true));

try {
  $stmt = $db->prepare("INSERT INTO application (name, phone, email, day, month, year, sex, bio) VALUES (?, ?, ?, ?, ?, ?,?, ?)");
  $stmt->execute([$name, $phone, $email, $day, $month, $year, $sex, $biography]);
  $application_id = $db->lastInsertId();
  $stmt = $db->prepare("INSERT INTO lang (application_id, lan) VALUES (?, ?)");
  foreach ($lang as $lan) {
    $stmt->execute([$application_id, $lan]);
  }
} catch (PDOException $e) {
  print('Error : ' . $e->getMessage());
  exit();
}
header('Location: ?save=1');
?>