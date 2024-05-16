<?php

include ('auth.php');
printf('<a href=authout.php>выйти</a>');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  try {
    $stmt = $db->prepare("SELECT application_id, name, phone,email, day, month, year, pol, biography FROM application");
    $stmt->execute();
    $values = $stmt->fetchAll(PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    print ('Error : ' . $e->getMessage());
    exit();
  }
  $messages = array();

  $errors = array();
  $errors['error_id'] = empty ($_COOKIE['error_id']) ? '' : $_COOKIE['error_id'];
  $errors['name'] = !empty ($_COOKIE['name_error']);
  $errors['name2'] = !empty ($_COOKIE['name_error2']);
  $errors['phone'] = !empty ($_COOKIE['phone_error']);
  $errors['phone2'] = !empty ($_COOKIE['phone_error2']);
  $errors['year'] = !empty ($_COOKIE['year_error']);
  $errors['email1'] = !empty ($_COOKIE['email_error1']);
  $errors['email2'] = !empty ($_COOKIE['email_error2']);
  $errors['languages'] = !empty ($_COOKIE['languages_error']);
  $errors['biography'] = !empty ($_COOKIE['biography_error']);

  if ($errors['name']) {
    setcookie('name_error', '', 100000);
    $messages['name'] = '<p class="msg">Заполните поле ФИО</p>';
  } else if ($errors['name2']) {
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
  $_SESSION['token'] = bin2hex(random_bytes(32));
  $_SESSION['login'] = $validUser;

  include ('db.php');
  exit();
} else {

  if (!empty ($_POST['token']) && hash_equals($_POST['token'], $_SESSION['token'])) {
    foreach ($_POST as $key => $value) {

      if (preg_match('/^clear(\d+)$/', $key, $matches)) {
        $app_id = $matches[1];
        setcookie('clear', $app_id, time() + 24 * 60 * 60);
        $stmt = $db->prepare("DELETE FROM application WHERE application_id = ?");
        $stmt->execute([$app_id]);
        $stmt = $db->prepare("DELETE FROM languages WHERE application_id = ?");
        $stmt->execute([$app_id]);
        $stmt = $db->prepare("DELETE FROM users WHERE application_id = ?");
        $stmt->execute([$app_id]);
      }
      if (preg_match('/^save(\d+)$/', $key, $matches)) {
        $app_id = $matches[1];
        $dates = array();
        $dates['name'] = $_POST['name' . $app_id];
        $dates['phone'] = $_POST['phone' . $app_id];
        $dates['email'] = $_POST['email' . $app_id];
        $dates['day'] = $_POST['day' . $app_id];
        $dates['month'] = $_POST['month' . $app_id];
        $dates['year'] = $_POST['year' . $app_id];
        $dates['pol'] = $_POST['pol' . $app_id];
        $languages = $_POST['languages' . $app_id];
        $dates['biography'] = $_POST['biography' . $app_id];

        $name = $dates['name'];
        $phone = $dates['phone'];
        $email = $dates['email'];
        $day = $dates['day'];
        $month = $dates['month'];
        $year = $dates['year'];
        $pol = $dates['pol'];
        $biography = $dates['biography'];

        if (empty ($name)) {
          setcookie('name_error', '1', time() + 24 * 60 * 60);
          $errors = TRUE;
        } else if (!preg_match('/^[A-Z]{1}[a-z]{1,20}$/', $name)) {
          setcookie('name_error2', '1', time() + 24 * 60 * 60);
          $errors = TRUE;
        }
        if (empty ($phone)) {
          setcookie('phone_error', '1', time() + 24 * 60 * 60);
          $errors = TRUE;
        } else if (!preg_match('/^(8|\+7)[-\(]?\d{3}\)?-?\d{3}-?\d{2}-?\d{2}$/', $phone)) {
          setcookie('phone_error2', '2', time() + 24 * 60 * 60);
          $errors = TRUE;
        }
        if (empty ($email)) {
          setcookie('email_error1', '1', time() + 24 * 60 * 60);
          $errors = TRUE;
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
          setcookie('email_error2', '1', time() + 24 * 60 * 60);
          $errors = TRUE;
        }
        if (2024 - $year < 18) {
          setcookie('year_error', '1', time() + 30 * 24 * 60 * 60);
          $errors = TRUE;
        }
        if (empty ($languages)) {
          setcookie('languages_error', '1', time() + 24 * 60 * 60);
          $errors = TRUE;
        }
        if (empty ($biography)) {
          setcookie('biography_error', '1', time() + 24 * 60 * 60);
          $errors = TRUE;
        }
        if ($errors) {
          setcookie('error_id', $app_id, time() + 24 * 60 * 60);
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
          setcookie('error_id', '', 100000);
        }
        $stmt = $db->prepare("SELECT name,phone, email, day, month, year, pol, biography FROM application WHERE application_id = ?");
        $stmt->execute([$app_id]);
        $old_dates = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $db->prepare("SELECT language_id FROM languages WHERE application_id = ?");
        $stmt->execute([$app_id]);
        $old_languages = $stmt->fetchAll(PDO::FETCH_COLUMN);
        if (array_diff($dates, $old_dates[0])) {
          $stmt = $db->prepare("UPDATE application SET name = ?, phone=?, email = ?, day=?, month=?, year = ?, pol = ?, biography = ? WHERE application_id = ?");
          $stmt->execute([$dates['name'], $dates['phone'],$dates['email'],$dates['day'],$dates['month'], $dates['year'], $dates['pol'], $dates['biography'], $app_id]);
        }
        if (array_diff($languages, $old_languages) || count($languages) != count($old_languages)) {
          $stmt = $db->prepare("DELETE FROM languages WHERE application_id = ?");
          $stmt->execute([$app_id]);
          $stmt = $db->prepare("INSERT INTO languages (application_id, language_id) VALUES (?, ?)");
          foreach ($languages as $language_id) {
            $stmt->execute([$app_id, $language_id]);
          }
        }
      }
    }
  } else {
    die ('Ошибка CSRF: недопустимый токен');
  }
  header('Location: index.php');
}