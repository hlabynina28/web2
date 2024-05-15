<?php

session_start();
session_destroy();
setcookie('name_value', '', 100000);
setcookie('tel_value', '',1000000);
setcookie('email_value', '', 100000);
setcookie('day_value', '', 100000);
setcookie('month_value', '', 100000);
setcookie('year_value', '', 100000);
setcookie('sex_value', '', 100000);
setcookie('hand_value', '', 100000);
setcookie('lang_value', '', 100000);
setcookie('biography_value', '', 100000);
setcookie('checkboxContract_value', '', 100000);
header('Location: ./');
exit();