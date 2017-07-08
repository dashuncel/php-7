<?php
  include_once __DIR__.DIRECTORY_SEPARATOR.'Gd.php';
  
  session_start();

  $total = $_SESSION['total'];
  $right = $_SESSION['right'];
  $name = $_SESSION['name'];
  $titul = $_SESSION['titul'];

  if ($right == $total) {
    $text = "Поздравляю, $name \nВы успешно прошли тестирование \nи получаете звание $titul";
    $textColor=imagecolorallocate($image, 123, 104, 238);
  }
  else {
    $text = "Сожалеем, $name \n Тестирование не пройдено";
    $textColor=imagecolorallocate($image, 139, 0, 0);
  }

  $img  = new Gd(400, 300, $text, $textColor);
  $img->generatge;
 /* unset($_SESSION['data']);
  unset($_SESSION['titul']);
  unset($_SESSION['name']);
  unset($_SESSION['total']);
  unset($_SESSION['right']);
  session_destroy();
*/
?>