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

    <div class="form-content">
        
      <div class="form-item">
        <label for="name"> ФИО</label>
        <input type="text" id="name" name="name"/>
      </div>

      <div class="form-item">
        <label for="phone"> Телефон</label>
        <input type="tel" id="tel" name="tel"/>
      </div>

      <div class="d-item">
         <label for="email">Email:</label>
         <input type="email" id="email" name="email" />
      </div>

      <div class="form-item">
            <div class="d-item">
                <span>Число:</span>
                    <select name="day">
                    <?php
                    for ($i = 1; $i < 32; $i++) {
                        printf('<option value="%d">%d </option>', $i, $i);
                    }
                    ?>
                    </select>
            </div>
            
            <div class="d-item">
                <span>Месяц:</span>
                    <select name="month">
                    <?php
                    for ($i = 1; $i < 13; $i++) {
                        printf('<option value="%d">%d </option>', $i, $i);
                    }
                    ?>
                    </select>
            </div>
            
            <div class="d-item">
                <span>Год рождения:</span>
                    <select name="year">
                        <?php
                        for ($i = 2023; $i >= 1900; $i--) {
                            printf('<option value="%d">%d год</option>', $i, $i);
                        }
                        ?>
                    </select>
            </div>
       </div>


      <div class="form-item">
        <p>Пол:</p>
        <ul>
        <li>
            <input type="radio" id="radioF" name="sex" value="female">
            <label for="radioFemale">Женский</label>
          </li>
          <li>
            <input type="radio" id="radioM" name="sex" value="male" checked>
            <label for="radioMale">Мужской</label>
          </li>
        </ul>
      </div>

      <div class="form-item">
        <p>Любимый язык программирования:</p>
        <ul>
          <li>
            <input type="checkbox" id="Python" name="lang[]" value='Python'>
            <label for="Python">Python</label>
          </li>
          <li>
            <input type="checkbox" id="JS" name="lang[]" value='JS'>
            <label for="JS">JS</label>
          </li>
          <li>
            <input type="checkbox" id="C++" name="lang[]" value='C++'>
            <label for="C++">C++</label>
          </li>
          <li>
            <input type="checkbox" id="Java" name="lang[]" value='Java'>
            <label for="Java">Java</label>
          </li>
          <li>
            <input type="checkbox" id="PHP" name="lang[]" value='PHP'>
            <label for="PHP">PHP</label>
          </li>
        </ul>
      </div>

      
      <div class="form-item">
        <p class="big-text">Расскажи о себе:</p>
        <p class="small-text">(макс. 128 символов)</p>
        <textarea name="biography" cols=24 rows=4 maxlength=128 spellcheck="false"></textarea>
      </div>
    </div>  

    <div class="send">
      <div class="contract">
        <input type="checkbox" id="checkboxContract" name="checkboxContract">
        <label for="checkboxContract">С контрактом ознакомлен</label>
      </div>
      <input class="btn" type="submit" name="submit" value="Отправить" />
    </div>
  </form>

</body> 

</html>