<?php
header('Content-Type: text/html; charset=UTF-8');

 $user = 'u59174';
 $pass = '4061054';
 $db = new PDO('mysql:host=localhost;dbname=u59174', $user, $pass, array(PDO::ATTR_PERSISTENT => true));


//отправляем данные без измненения состояния сервера
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  $messages = array(); //для сообщений об ошибках в полях формы
  $messages1 = array(); //для сообщений о логине и о результах отправки формы

  //если есть кука save то данные на сервер сохранились и вывод сообщение о полож результате
  if (!empty($_COOKIE['save'])) {
    setcookie('save', '', 100000);
    $messages1['good'] = '<div class="good">Спасибо, результаты сохранены</div>';
    if (!empty($_COOKIE['password'])) {
      $messages1['login'] = sprintf('<div class="login">Логин: <strong>%s</strong><br>
      Пароль: <strong>%s</strong><br>Войдите в аккаунт с этими данными,<br>чтобы изменить введёные значения формы</div>',
        strip_tags($_COOKIE['login']),
        strip_tags($_COOKIE['password'])
      );
    }
    setcookie('login', '', 100000);
    setcookie('password', '', 100000);
  }

  $errors = array();
  $errors['name'] = !empty($_COOKIE['name_error']);
  $errors['name2'] = !empty($_COOKIE['name_error2']);
  $errors['phone'] = !empty($_COOKIE['phone_error']);
  $errors['phone2'] = !empty($_COOKIE['phone_error2']);
  $errors['year'] = !empty($_COOKIE['year_error']);
  $errors['email1'] = !empty($_COOKIE['email_error1']);
  $errors['email2'] = !empty($_COOKIE['email_error2']);
  $errors['languages'] = !empty($_COOKIE['languages_error']);
  $errors['biography'] = !empty($_COOKIE['biography_error']);
  $errors['checkboxContract'] = !empty($_COOKIE['checkboxContract_error']);

  if ($errors['name']) {
    setcookie('name_error', '', 100000);
    $messages['name'] = '<p class="msg">Заполните поле ФИО</p>';
  }
  else if ($errors['name2']) {
    setcookie('name_error2', '', 10000);
    $messages['name2'] = '<p class="msg">Имя должно начинаться с заглавной буквы и содержать не более 20 символов</p>';
  }
  if ($errors['email1']) {
    setcookie('email_error1', '', 100000);
    $messages['email1'] = '<p class="msg">Заполните поле email</p>';
  } else if ($errors['email2']) {
    setcookie('email_error2', '', 100000);
    $messages['email2'] = '<p class="msg">Неверно заполнено поле email</p>';
  }
  if ($errors['phone']) {
    setcookie('phone_error', '', 100000);
    $messages['phone'] = '<p class="msg">Заполните поле телефон</p>';
  }
  if ($errors['phone2']) {
    setcookie('phone_error2', '', 10000);
    $messages['phone2'] = '<p class="msg">Неверно заполнено поле телефон</p>';
  }
  if ($errors['year']) {
    setcookie('year_error', '', 100000);
    $messages['year'] = '<p class="msg">Ваш должно быть 18 лет или больше</p>';
  }
  if ($errors['languages']) {
    setcookie('languages_error', '', 100000);
    $messages['languages'] = '<p class="msg">Выберите язык программирования</p>';
  }
  if ($errors['biography']) {
    setcookie('biography_error', '', 100000);
    $messages['biography'] = '<p class="msg">Расскажи о себе что-нибудь</p>';
  }
  if ($errors['checkboxContract']) {
    setcookie('checkboxContract_error', '', 100000);
    $messages['checkboxContract'] = '<p class="msg">Ознакомьтесь с контрактом</p>';
  }

  $values = array();
  $values['name'] = empty($_COOKIE['name_value']) ? '' : $_COOKIE['name_value'];
  $values['phone'] = empty($_COOKIE['phone_value']) ? '' : $_COOKIE['phone_value'];
  $values['email'] = empty($_COOKIE['email_value']) ? '' : $_COOKIE['email_value'];
  $values['day'] = empty($_COOKIE['day_value']) ? '' : $_COOKIE['day_value'];
  $values['month'] = empty($_COOKIE['month_value']) ? '' : $_COOKIE['month_value'];
  $values['year'] = empty($_COOKIE['year_value']) ? '' : $_COOKIE['year_value'];
  $values['pol'] = empty($_COOKIE['pol_value']) ? '' : $_COOKIE['pol_value'];
  $values['languages'] = empty($_COOKIE['languages_value']) ? '' : $_COOKIE['languages_value'];
  $values['biography'] = empty($_COOKIE['biography_value']) ? '' : $_COOKIE['biography_value'];
  $values['checkboxContract'] = empty($_COOKIE['checkboxContract_value']) ? '' : $_COOKIE['checkboxContract_value'];



  if (!empty($_COOKIE[session_name()]) && session_start() && !empty($_SESSION['login'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
    $login = $_SESSION['login'];
    try {
      $stmt = $db->prepare("SELECT application_id FROM users WHERE login = ?");
      $stmt->execute([$login]);
      $app_id = $stmt->fetchColumn();

      $stmt = $db->prepare("SELECT name, phone, email, day, month, year, pol, biography FROM application WHERE application_id = ?");
      $stmt->execute([$app_id]);
      $dates = $stmt->fetchAll(PDO::FETCH_ASSOC);

      $stmt = $db->prepare("SELECT language_id FROM languages WHERE application_id = ?");
      $stmt->execute([$app_id]);
      $languages = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

      //если в базе данных и введеное последний раз совпадает, то в поле записываем этот результат,
      // если же не совпадают, то оставляем последнее введеное
      if (count(array_filter($errors)) === 0) {
        if (!empty($dates[0]['name'])) {
          $values['name'] = $dates[0]['name'];
        }
        if (!empty($dates[0]['phone'])) {
          $values['phone'] = $dates[0]['phone'];
        }
        if (!empty($dates[0]['email'])) {
          $values['email'] = $dates[0]['email'];
        }
        if (!empty($dates[0]['day'])) {
          $values['day'] = $dates[0]['day'];
        }
        if (!empty($dates[0]['month'])) {
          $values['month'] = $dates[0]['month'];
        }
        if (!empty($dates[0]['year'])) {
          $values['year'] = $dates[0]['year'];
        }
        if (!empty($dates[0]['pol'])) {
          $values['pol'] = $dates[0]['pol'];
        }
        if (!empty($languages)) {
          $values['languages'] = serialize($languages);
        }
        if (!empty($dates[0]['biography'])) {
          $values['biography'] = $dates[0]['biography'];
        }
      } 
     


    } catch (PDOException $e) {
      print('Error : ' . $e->getMessage());
      exit();
    }
  printf('<div id="header"><p>Вход с логином %s; uid: %d</p><a href=logout.php>Выйти</a></div>', $_SESSION['login'], $_SESSION['uid']);
  
  
  }

  include('form.php');
  //вход с логином изменяя состояние сервера
} else {
  $errors = FALSE;

  $name = $_POST['name'];
  $email = $_POST['email'];
  $day = $_POST['day'];
  $month = $_POST['month'];
  $year = $_POST['year'];
  $pol = $_POST['pol'];
  $phone = $_POST['phone'];
  $biography = $_POST['biography'];
  $checkboxContract = $_POST['checkboxContract'];
  
  $languages = $_POST["languages"];
 

  if (empty($name)) {
    setcookie('name_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else if (!preg_match('/^[A-Z]{1}[a-z]{1,20}$/', $name)){
    setcookie('name_error2', '1', time()+24*60*60);
    $errors=TRUE;
  } 
  setcookie('name_value', $name, time() + 30 * 24 * 60 * 60);
  if (empty($phone)) {
    setcookie('phone_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  } 
  else if (!preg_match('/^(8|\+7)[-\(]?\d{3}\)?-?\d{3}-?\d{2}-?\d{2}$/', $phone)){
    setcookie('phone_error2', '2', time()+24*60*60);
    $errors=TRUE;
  }
  setcookie('phone_value', $phone, time() + 30 * 24 * 60 * 60);
  if (empty($email)) {
    setcookie('email_error1', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    setcookie('email_error2', '1', time() + 24 * 60 * 60);
    setcookie('email_value', $email, time() + 30 * 24 * 60 * 60);
    $errors = TRUE;
  } 

    setcookie('email_value', $email, time() + 30 * 24 * 60 * 60);

  setcookie('day_value', $day, time() + 30 * 24 * 60 * 60);
  setcookie('month_value', $month, time() + 30 * 24 * 60 * 60);
  if (2024 - $year < 18) {
    setcookie('year_error', '1', time() + 30 * 24 * 60 * 60);
    $errors = TRUE;
  } 

    setcookie('year_value', $year, time() + 30 * 24 * 60 * 60);

  setcookie('pol_value', $pol, time() + 30 * 24 * 60 * 60);
  if (empty($languages)) {
    setcookie('languages_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  } 

    setcookie('languages_value', serialize($languages), time() + 30 * 24 * 60 * 60);

  if (empty($biography)) {
    setcookie('biography_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  } 
  setcookie('biography_value', $biography, time() + 30 * 24 * 60 * 60);
  if ($checkboxContract == '') {
    setcookie('checkboxContract_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  } 
  setcookie('checkboxContract_value', $checkboxContract, time() + 30 * 24 * 60 * 60);


  if ($errors) {
    header('Location: index.php');
    exit();
  } else {
    setcookie('name_error', '', 100000);
    setcookie('name_error2', '', 100000);
    setcookie('phone_error', '', 100000);
    setcookie('phone_error2', '', 10000);
    setcookie('email_error1', '', 100000);
    setcookie('year__error', '', 100000);
    setcookie('email_error2', '', 100000);
    setcookie('languages_error', '', 100000);
    setcookie('biography_error', '', 100000);
    setcookie('checkboxContract_error', '', 100000);
  }

  if (!empty($_COOKIE[session_name()]) && session_start() && !empty($_SESSION['login'])) {
    if (!empty($_POST['token']) && hash_equals($_POST['token'], $_SESSION['token'])) {
      $login = $_SESSION['login'];
      try {
        $stmt = $db->prepare("SELECT application_id FROM users WHERE login = ?");
        $stmt->execute([$login]);
        $app_id = $stmt->fetchColumn();

        $stmt = $db->prepare("UPDATE application SET name = ?, phone=?, email = ?, day=?, month=?, year = ?, pol = ?,  biography = ?
          WHERE application_id = ?");
        $stmt->execute([$name, $phone, $email, $day, $month, $year, $pol, $biography, $app_id]);

        $stmt = $db->prepare("SELECT language_id FROM languages WHERE application_id = ?");
        $stmt->execute([$app_id]);
        $lang = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

        if (array_diff($languages, $lang) || array_diff($lang, $languages)) {
          $stmt = $db->prepare("DELETE FROM languages WHERE application_id = ?");
          $stmt->execute([$app_id]);

          $stmt = $db->prepare("INSERT INTO languages (application_id, language_id) VALUES (?, ?)");
          foreach ($languages as $language_id) {
            $stmt->execute([$app_id, $language_id]);
          }
        }

      } catch (PDOException $e) {
        print('Error : ' . $e->getMessage());
        exit();
      }
    } else {
      die('Ошибка CSRF: недопустимый токен');
    }
  } else {
    $login = 'user' . rand(1, 1000);
    $password = rand(1, 100);
    setcookie('login', $login);
    setcookie('password', $password);
    try {
      $stmt = $db->prepare("INSERT INTO application (name, phone,email, day, month, year, pol, biography) VALUES (?, ?, ?, ?, ?, ?,?,?)");
      $stmt->execute([$name, $phone, $email, $day, $month, $year, $pol, $biography]);
      $application_id = $db->lastInsertId();
      $stmt = $db->prepare("INSERT INTO languages (application_id, language_id) VALUES (?, ?)");
      foreach ($languages as $language_id) {
        $stmt->execute([$application_id, $language_id]);
      }
      $stmt = $db->prepare("INSERT INTO users (application_id, login, password) VALUES (?, ?, ?)");
      $stmt->execute([$application_id, $login, md5($password)]);
    } catch (PDOException $e) {
      print('Error : ' . $e->getMessage());
      exit();
    }
  }
  setcookie('save', '1');
  header('Location: ./');
}