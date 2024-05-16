<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link rel="stylesheet" href="./main.css">
</head>

<body>

    <form action="" method="POST">

        <table>
            <h1>Страница администратора</h1>
            <h4 class="langProg">Информация об ЯП:</h4>
            <?php


            $stmt = $db->prepare("SELECT count(application_id) from languages where language_id = 1;");
            $stmt->execute();
            $JS = $stmt->fetchColumn();
            $stmt = $db->prepare("SELECT count(application_id) from languages where language_id = 2;");
            $stmt->execute();
            $Python = $stmt->fetchColumn();
            $stmt = $db->prepare("SELECT count(application_id) from languages where language_id = 3;");
            $stmt->execute();
            $C = $stmt->fetchColumn();

            echo "JS: ";
            echo (empty ($JS) ? '0' : $JS) . "</br>";
            echo "Python: ";
            echo (empty ($Python) ? '0' : $Python) . "</br>";
            echo "C++: ";
            echo (empty ($C) ? '0' : $C) . "</br>";

            echo '<div class="msgbox">';
            if (!empty ($messages)) {
                foreach ($messages as $message) {
                    print ($message);
                }
            }
            echo '</div>';
            ?>
            <tr>
                <th>id</th>
                <th>Имя</th>
                <th>Телефон</th>
                <th>email</th>
                <th>День</th>
                <th>Месяц</th>
                <th>Год</th>
                <th>Пол</th>
                <th>ЯП</th>
                <th>Биография</th>
                <th>Изменить</th>
            </tr>
            <?php
            foreach ($values as $value) {
                echo '<tr>
                            <td style="font-weight: 700;">';
                print ($value['application_id']);
                echo '</td>
                            <td class="name">
                                <input name="name' . $value['application_id'] . '" value="';
                print (htmlspecialchars(strip_tags($value['name'])));
                echo '">
                            </td>
                            <td class="phone">
                            <input  name="phone' . $value['application_id'] . '" value="';
                print (htmlspecialchars(strip_tags($value['phone'])));
                echo '">
                            </td>
                            <td class="email">
                                <input  name="email' . $value['application_id'] . '" value="';
                print (htmlspecialchars(strip_tags($value['email'])));
                echo '">
                            </td>
                           
                            <td class="day">
                            <select name="day' . $value['application_id'] . '">';
                for ($i = 1; $i <= 31; $i++) {
                    if ($i == $value['day']) {
                        printf('<option selected value="%d">%d </option>', $i, $i);
                    } else {
                        printf('<option value="%d">%d </option>', $i, $i);
                    }
                }
                echo '</select>
                            </td>
                            <td class="month">
                            <select name="month' . $value['application_id'] . '">';
                for ($i = 1; $i <= 12; $i++) {
                    if ($i == $value['month']) {
                        printf('<option selected value="%d">%d </option>', $i, $i);
                    } else {
                        printf('<option value="%d">%d </option>', $i, $i);
                    }
                }
                echo '</select>
                            </td>
                             <td class="year">
                                <select class="year" name="year' . $value['application_id'] . '">';
                for ($i = 2024; $i >= 1922; $i--) {
                    if ($i == $value['year']) {
                        printf('<option selected value="%d">%d </option>', $i, $i);
                    } else {
                        printf('<option value="%d">%d </option>', $i, $i);
                    }
                }
                echo '</select>
                            </td>
                            <td> 
                                <div >
                                    <input type="radio" id="radioMale' . $value['application_id'] . '" name="pol' . $value['application_id'] . '" value="male" ';
                if ($value['pol'] == 'male')
                    echo 'checked';
                echo '>
                                    <label for="radioMale' . $value['application_id'] . '">Мужчина</label>
                                </div>
                                <div >
                                    <input type="radio" id="radioFemale' . $value['application_id'] . '" name="pol' . $value['application_id'] . '" value="female" ';
                if ($value['pol'] == 'female')
                    echo 'checked';
                echo '>
                                    <label for="radioFemale' . $value['application_id'] . '">Женщина</label>
                                </div>
                            </td>
                            ';
                $stmt = $db->prepare("SELECT language_id FROM languages WHERE application_id = ?");
                $stmt->execute([$value['application_id']]);
                $languages = $stmt->fetchAll(PDO::FETCH_COLUMN);
                echo '<td class="languages">
                                <div>
                                    <input type="checkbox" id="JS' . $value['application_id'] . '" name="languages' . $value['application_id'] . '[]" value="1"' . (in_array(1, $languages) ? ' checked' : '') . '>
                     
                                    <label for="JS' . $value['application_id'] . '">JS</label>
                                </div>
                                <div >
                                    <input type="checkbox" id="Python' . $value['application_id'] . '" name="languages' . $value['application_id'] . '[]" value="2"' . (in_array(2, $languages) ? ' checked' : '') . '>
                            
                                    <label for="Python' . $value['application_id'] . '">Python</label>
                                </div>
                                <div >
                                    <input type="checkbox" id="C++' . $value['application_id'] . '" name="languages' . $value['application_id'] . '[]" value="3"' . (in_array(3, $languages) ? ' checked' : '') . '>
                         
                                    <label for="C++' . $value['application_id'] . '">C++</label>
                                </div>
                            </td>
                            <td class="biography">
                                <textarea  name="biography' . $value['application_id'] . '" id="" cols="15" rows="4" maxlength="128">';
                print htmlspecialchars(strip_tags($value['biography']));
                echo '</textarea>
                            </td>
                            <td >
                            <div class="change">
                       
                                <div class="column-item button_save">
                                    <input name="save' . $value['application_id'] . '" type="submit" value="save' . $value['application_id'] . '"/>
                                </div>
                              
                                <div class="column-item button_clear">
                                    <input name="clear' . $value['application_id'] . '" type="submit" value="clear' . $value['application_id'] . '"/>
                                </div>
                            </div>
                            </td>


                        </tr>';
            }
            ?>
        </table>
        <input type="hidden" name="token" value="<?= $_SESSION['token']; ?>" />
    </form>
</body>

</html>