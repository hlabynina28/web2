<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>
  <link rel="stylesheet" href="style.css">
  <title>Form</title>
</head>

<body>
  <form action="" method="POST">
    <div class="form-head">
        <h1>Форма!</h1>
    </div>

    <?php
    if (!empty($messages)) {
      print('<div id="messages">');
      // Выводим все сообщения.
      foreach ($messages as $message) {
        print($message);
      }
      print('</div>');
    }
    ?>

    <div class="form-content">
        
      <div class="form-item">
        <p <?php if ($errors['name']) {print 'class="error"';} ?>> Name</p>
        <input class="line" name="name" value="<?php echo $values['name']; ?>" />
      </div>

      <div class="form-item">
        <p <?php if ($errors['tel']) {print 'class="error"';} ?>> Telephone</p>
        <input type="tel" id="tel" name="tel" value="<?php echo $values['tel'];?>" />
      </div>

      <div class="form-item">
         <p <?php if  ($errors['email1'] || $errors['email2']) {print 'class="error"';} ?>>Email</p>
         <input class="line" name="email" value="<?php print $values['email']; ?>" />
      </div>

      <div class="form-item">
            <div class="d-item">
                <span <?php if ($errors['day']) {print 'class="error"';} ?>>Число:</span>
                    <select name="day">
                    <?php
                    for ($i = 1; $i < 32; $i++) {
                      if ($i == $values['day']) {
                        printf('<option selected value="%d">%d день</option>', $i, $i);
                      } else {
                      printf('<option value="%d">%d день</option>', $i, $i);
                      }
                    }
                    ?>
                    </select>
            </div>
            
            <div class="d-item">
                <span  <?php if ($errors['month']) {print 'class="error"';} ?>> Месяц:</span>
                    <select name="month">
                    <?php
                    for ($i = 1; $i < 13; $i++) {
                      if ($i == $values['month']) {
                        printf('<option selected value="%d">%d месяц</option>', $i, $i);
                      } else {
                      printf('<option value="%d">%d месяц</option>', $i, $i);
                      }
                    }
                    ?>
                    </select>
            </div>
            
            <div class="d-item">
                <span  <?php if ($errors['year1']||$errors['year2']) {print 'class="error"';} ?>>Год рождения:</span>
                    <select name="year">
                        <?php
                        for ($i = 2023; $i >= 1900; $i--) {
                          if ($i == $values['year']) {
                            printf('<option selected value="%d">%d год</option>', $i, $i);
                          } else {
                          printf('<option value="%d">%d год</option>', $i, $i);
                          }
                        }
                        ?>
                    </select>
            </div>
       </div>


      <div class="form-item">
        <ul>
        <li>
            <input type="radio" id="radioF" name="sex" value="female" checked <?php if ($values['sex'] == 'female') {print 'checked';} ?>>
            <label for="radioFemale">Женский</label>
          </li>
          <li>
            <input type="radio" id="radioM" name="sex" value="male" <?php if ($values['sex'] == 'male') {print 'checked';} ?>>>
            <label for="radioMale">Мужской</label>
          </li>
        </ul>
      </div>

      <div class="form-item">
        <p <?php if ($errors['lang']) {print 'class="error"';}?>>Любимый язык программирования:</p>
        <ul>
          <li>
            <input type="checkbox" id="Python" name="lang[]" value='Python' <?php if (isset($values['lang']) && !empty($values['lang']) && in_array('Python', unserialize($values['lang']))) {print 'checked';}?>`>
            <label for="Python">Python</label>
          </li>
          <li>
            <input type="checkbox" id="JS" name="lang[]" value='JS' <?php if (isset($values['lang']) && !empty($values['lang']) && in_array('JS', unserialize($values['lang']))) {print 'checked';}?>>
            <label for="JS">JS</label>
          </li>
          <li>
            <input type="checkbox" id="C++" name="lang[]" value='C++' <?php if (isset($values['lang']) && !empty($values['lang']) && in_array('C++', unserialize($values['lang']))) {print 'checked';}?>>
            <label for="C++">C++</label>
          </li>
          <li>
            <input type="checkbox" id="Java" name="lang[]" value='Java' <?php if (isset($values['lang']) && !empty($values['lang']) && in_array('Java', unserialize($values['lang']))) {print 'checked';}?>>
            <label for="Java">Java</label>
          </li>
          <li>
            <input type="checkbox" id="PHP" name="lang[]" value='PHP' <?php if (isset($values['lang']) && !empty($values['lang']) && in_array('PHP', unserialize($values['lang']))) {print 'checked';}?>>
            <label for="PHP">PHP</label>
          </li>
        </ul>
      </div>

      
      <div class="form-item">
        <p class="big-text <?php if ($errors['biography1'] || $errors['biography2']) {print 'error';} ?>"> Расскажи о себе:</p>
        <textarea name="biography" cols=24 rows=4 maxlength=128 spellcheck="false"><?php if (!empty($values['biography'])) {print $values['biography'];} ?></textarea>
      </div>
    </div>

    <div class="send">
      <div class="contract">
        <input type="checkbox" id="checkboxContract" name="checkboxContract" <?php if ($values['checkboxContract'] == '1') {print 'checked';} ?>>
        <label for="checkboxContract" <?php if ($errors['checkboxContract']) {print 'class="error"';} ?>>С контрактом ознакомлен</label>
      </div>
      <input class="btn" type="submit" name="submit" value="Отправить" />
    </div>
  </form>

</body> 

</html>