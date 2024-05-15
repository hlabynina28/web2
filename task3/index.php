<?php

header('Content-Type: text/html; charset=UTF-8');

$user = 'u59174';
$pass = '4061054';
$db = new PDO('mysql:host=localhost;dbname=u59174', $user, $pass, array(PDO::ATTR_PERSISTENT => true));

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  $messages = array();
  $messages1 = array();
  if (!empty($_COOKIE['save'])) {
    setcookie('save', '', 100000);
    $messages1['allok'] = '<div class="good">Спасибо, результаты сохранены</div>';
    if (!empty($_COOKIE['password'])) {
      $messages1['login'] = sprintf('<div class="login">Логин: <strong>%s</strong><br>
        Пароль: <strong>%s</strong><br>Войдите в аккаунт с этими данными,<br>чтобы изменить введёные значения формы</div>',
        strip_tags($_COOKIE['login']),
        strip_tags($_COOKIE['password']));
    }
    setcookie('login', '', 100000);
    setcookie('password', '', 100000);
  }
  $errors = array();
  $errors['name'] = !empty($_COOKIE['name_error']);
  $errors['tel']=!empty($_COOKIE['tel_error']);
  $errors['email1'] = !empty($_COOKIE['email_error1']);
  $errors['email2'] = !empty($_COOKIE['email_error2']);
  $errors['day'] = !empty($_COOKIE['day_error']);
  $errors['month'] = !empty($_COOKIE['month_error']);
  $errors['year1'] = !empty($_COOKIE['year_error1']);
  $errors['year2'] = !empty($_COOKIE['year_error2']);
  $errors['sex'] = !empty($_COOKIE['sex_error']);
  $errors['lang'] = !empty($_COOKIE['lang_error']);
  $errors['biography1'] = !empty($_COOKIE['biography_error1']);
  $errors['biography2'] = !empty($_COOKIE['biography_error2']);
  $errors['checkboxContract'] = !empty($_COOKIE['checkboxContract_error']);
 
  if ($errors['name']) {
    setcookie('name_error', '', 100000);
    $messages['name'] = '<p class="msg">Заполните имя</p>';
  }

  if ($errors['tel']){
    setcookie('tel_error', '', 100000);
    $messages['tel'] = '<p claass="msg"> Заполните номер телефона</p>';
  }

  if ($errors['email1']) {
    setcookie('email_error1', '', 100000);
    $messages['email1'] = '<p class="msg">Заполните email</p>';
  } else if ($errors['email2']) {
    setcookie('email_error2', '', 100000);
    $messages['email2'] = '<p class="msg">Корректно* заполните email</p>';
  }

  if ($errors['day']){
    setcookie('day_error', '', 100000);
    $messages['day'] = '<p class="msg"> Выберете день</p>';
  }
  if ($errors['month']){
    setcookie('month_error', '', 100000);
    $messages['month'] = '<p class="msg"> Выберете месяц</p>';
  }
  if ($errors['year1']) {
    setcookie('year_error1', '', 100000);
    $messages['year1'] = '<p class="msg">Неправильный формат ввода года</p>';
  } else if ($errors['year2']) {
    setcookie('year_error2', '', 100000);
    $messages['year2'] = '<p class="msg">Вам должно быть 14 лет</p>';
  }

  if ($errors['sex']) {
    setcookie('sex_error', '', 100000);
    $messages['sex'] = '<p class="msg">Выберите пол</p>';
  }

  if ($errors['lang']) {
    setcookie('lang_error', '', 100000);
    $messages['lang'] = '<p class="msg">Выберите хотя бы один <br> язык программирования</p>';
  }
  
  if ($errors['biography1']) {
    setcookie('biography_error1', '', 100000);
    $messages['biography1'] = '<p class="msg">Расскажи о себе что-нибудь</p>';
  } else if ($errors['biography2']) {
    setcookie('biography_error2', '', 100000);
    $messages['biography2'] = '<p class="msg">Недопустимый формат ввода <br> биографии</p>';
  }
  if ($errors['checkboxContract']) {
    setcookie('checkboxContract_error', '', 100000);
    $messages['checkboxContract'] = '<p class="msg">Ознакомьтесь с контрактом</p>';
  }

  $values = array();
  $values['name'] = empty($_COOKIE['name_value']) ? '' : $_COOKIE['name_value'];
  $values['tel'] = empty($_COOKIE['tel_value']) ? '' : $_COOKIE['tel_value'];
  $values['email'] = empty($_COOKIE['email_value']) ? '' : $_COOKIE['email_value'];
  $values['day'] = empty($_COOKIE['day_value']) ? '' : $_COOKIE['day_value'];
  $values['month'] = empty($_COOKIE['month_value']) ? '' : $_COOKIE['month_value'];
  $values['year'] = empty($_COOKIE['year_value']) ? '' : $_COOKIE['year_value'];
  $values['sex'] = empty($_COOKIE['sex_value']) ? '' : $_COOKIE['sex_value'];
  $values['lang'] = empty($_COOKIE['lang_value']) ? '' : $_COOKIE['lang_value'];
  $values['biography'] = empty($_COOKIE['biography_value']) ? '' : $_COOKIE['biography_value'];
  $values['checkboxContract'] = empty($_COOKIE['checkboxContract_value']) ? '' : $_COOKIE['checkboxContract_value'];
  
  if (count(array_filter($errors)) === 0 && !empty($_COOKIE[session_name()]) && session_start() && !empty($_SESSION['login'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
    $login = $_SESSION['login'];
    try {
      $stmt = $db->prepare("SELECT application_id FROM users WHERE login = ?");
      $stmt->execute([$login]);
      $app_id = $stmt->fetchColumn();

      $stmt = $db->prepare("SELECT name, phone, email, day, month, year, sex, bio FROM application WHERE application_id = ?");
      $stmt->execute([$app_id]);
      $dates = $stmt->fetchAll(PDO::FETCH_ASSOC);

      $stmt = $db->prepare("SELECT lan FROM lang WHERE application_id = ?");
      $stmt->execute([$app_id]);
      $lang = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

      if (!empty($dates[0]['name'])) {
        $values['name'] = htmlspecialchars(strip_tags($dates[0]['name']));
      }
      if (!empty($dates[0]['phone'])) {
        $values['tel'] = htmlspecialchars(strip_tags($dates[0]['phone']));
      }
      if (!empty($dates[0]['email'])) {
        $values['email'] = htmlspecialchars(strip_tags($dates[0]['email']));
      }
      if (!empty($dates[0]['day'])) {
        $values['day'] = htmlspecialchars(strip_tags($dates[0]['day']));
      }
      if (!empty($dates[0]['month'])) {
        $values['month'] = htmlspecialchars(strip_tags($dates[0]['month']));
      }
      if (!empty($dates[0]['year'])) {
        $values['year'] = htmlspecialchars(strip_tags($dates[0]['year']));
      }
      if (!empty($dates[0]['sex'])) {
        $values['sex'] = htmlspecialchars(strip_tags($dates[0]['sex']));
      }
      if (!empty($lang)) {
        $values['lang'] =  serialize($lang);
      }
      if (!empty($dates[0]['bio'])) {
        $values['biography'] = htmlspecialchars(strip_tags($dates[0]['bio']));
      }

    } catch (PDOException $e) {
        print('Error : ' . $e->getMessage());
        exit();
    }

    printf('<div id="header"><p>Вход с логином %s; uid: %d</p><a href=logout.php>Выйти</a></div>', $_SESSION['login'], $_SESSION['uid']);
  }
  include('form.php');
} else {
  $errors = FALSE;

  $name = $_POST['name'];
  $tel = $_POST['tel'];
  $day = $_POST['day'];
  $month = $_POST['month'];
  $email = $_POST['email'];
  $year = $_POST['year'];
  $sex = $_POST['sex'];
  if(isset($_POST["lang"])) {
    $lang = $_POST["lang"];
    $filtred_lang = array_filter($lang,
    function($value) {
      return($value == 'Python' || $value == 'JS' || $value == 'Java'|| $value == 'C++'|| $value == 'PHP');
    }
    );
  }
  $biography = $_POST['biography'];
  $checkboxContract = isset($_POST['checkboxContract']);

  if (empty($name)) {
    setcookie('name_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  } else {
    setcookie('name_value', $name, time() + 365 * 24 * 60 * 60);
  }

  if (empty($tel)) {
    setcookie('tel_error', '1', time()+24*60*60);
    $errors = TRUE;
  } else {
    setcookie('tel_value', $tel, time()+365*60*60*24);
  }

  if (empty($email)) {
    setcookie('email_error1', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    setcookie('email_error2', '1', time() + 24 * 60 * 60);
    setcookie('email_value', $email, time() + 365 * 24 * 60 * 60);
    $errors = TRUE;
  } else {
    setcookie('email_value', $email, time() + 30 * 24 * 60 * 60);
  }

  if (!is_numeric($year)) {
    setcookie('year_error1', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  } else if ((2023 - $year) < 14) {
    setcookie('year_error2', '1', time() + 24 * 60 * 60);
    setcookie('year_value', $year, time() + 30 * 24 * 60 * 60);
    $errors = TRUE;
  } else {
    setcookie('year_value', $year, time() + 365 * 24 * 60 * 60);
  }

  if (empty($sex)) {
    setcookie('sex_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  } else {
    setcookie('sex_value', $sex, time() + 365 * 24 * 60 * 60);
  }

  if (empty($lang)) {
    setcookie('lang_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  } else {
    setcookie('lang_value', serialize($lang), time() + 365 * 24 * 60 * 60);
  }

  if (empty($biography)) {
    setcookie('biography_error1', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  } else if (!preg_match('/^[\p{Cyrillic}\d\s,.!?-]+$/u', $biography)) {
    setcookie('biography_error2', '1', time() + 24 * 60 * 60);
    setcookie('biography_value', $biography, time() + 30 * 24 * 60 * 60);
    $errors = TRUE;
  } else {
    setcookie('biography_value', $biography, time() + 365 * 24 * 60 * 60);
  }

  if ($checkboxContract == '') {
    setcookie('checkboxContract_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  } else {
    setcookie('checkboxContract_value', $checkboxContract, time() + 365 * 24 * 60 * 60);
  }

  if ($errors) {
    header('Location: index.php');
    exit();
  }
  else {
    setcookie('name_error', '', 100000);
    setcookie('tel_error', '', 100000);
    setcookie('email_error1', '', 100000);
    setcookie('email_error2', '', 100000);
    setcookie('day_error', '', 100000);
    setcookie('month_error', '', 100000);
    setcookie('year_error1', '', 100000);
    setcookie('year_error2', '', 100000);
    setcookie('sex_error', '', 100000);
    setcookie('lang_error', '', 100000);
    setcookie('biography_error1', '', 100000);
    setcookie('biography_error2', '', 100000);
    setcookie('checkboxContract_error', '', 100000);
  }


  if (!empty($_COOKIE[session_name()]) && session_start() && !empty($_SESSION['login'])) {
    if (!empty($_POST['token']) && hash_equals($_POST['token'], $_SESSION['token'])) {
    $login = $_SESSION['login'];
    try {
      $stmt = $db->prepare("SELECT application_id FROM users WHERE login = ?");
      $stmt->execute([$login]);
      $app_id = $stmt->fetchColumn();

      $stmt = $db->prepare("UPDATE application SET name = ?, email = ?, year = ?, gender = ?, hand = ?, biography = ?
        WHERE application_id = ?");
      $stmt->execute([$name, $email, $year, $gender, $hand, $biography, $app_id]);

      $stmt = $db->prepare("SELECT superpower_id FROM abilities WHERE application_id = ?");
      $stmt->execute([$app_id]);
      $abil = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

      if (array_diff($abil, $abilities)) {
        $stmt = $db->prepare("DELETE FROM abilities WHERE application_id = ?");
        $stmt->execute([$app_id]);

        $stmt = $db->prepare("INSERT INTO abilities (application_id, superpower_id) VALUES (?, ?)");
        foreach ($abilities as $superpower_id) {
          $stmt->execute([$app_id, $superpower_id]);
        }
      }

    } catch (PDOException $e) {
        print('Error : ' . $e->getMessage());
        exit();
    }
    } else {
        die('Ошибка CSRF: недопустимый токен');
    }
  }
  else {
    $login = 'user' . rand(1, 1000);
    $password = rand(1, 100);
    setcookie('login', $login);
    setcookie('password', $password);
    try {
      $stmt = $db->prepare("INSERT INTO application (name, phone, email, day, month, year, sex, bio) VALUES (?, ?, ?, ?, ?, ?,?, ?)");
      $stmt->execute([$name, $tel, $email, $day, $month, $year, $sex, $biography]);
      $application_id = $db->lastInsertId();
      $stmt = $db->prepare("INSERT INTO lang (application_id, lan) VALUES (?, ?)");
      foreach ($lang as $lan) {
        $stmt->execute([$application_id, $lan]);
      }
    } catch (PDOException $e) {
      print('Error : ' . $e->getMessage());
      exit();
    }
  }

  setcookie('save', '1');
  header('Location: ./');
}