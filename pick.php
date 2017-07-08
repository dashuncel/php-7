<?php
  include_once __DIR__.DIRECTORY_SEPARATOR.'Gd.php';
  
  session_start();

  $total = $_SESSION['total'];
  $right = $_SESSION['right'];
  $name = $_SESSION['name'];
  $titul = $_SESSION['titul'];

  if ($right == $total) {
    $text = "Поздравляю, $name \nВы успешно прошли тестирование \nи получаете звание $titul";
  }
  else {
    $text = "Сожалеем, $name \n Тестирование не пройдено";
  }

  $img  = new Gd(600, 600, $text);
  $img->generate();
 /* unset($_SESSION['data']);
  unset($_SESSION['titul']);
  unset($_SESSION['name']);
  unset($_SESSION['total']);
  unset($_SESSION['right']);
  session_destroy();
*/
?>