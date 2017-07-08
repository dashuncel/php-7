<?php
 /*
  echo "<pre>";
  print_r($_SESSION);
  echo "</pre>";
*/
/*
  $total = $_SESSION['total'];
  $right = $_SESSION['right'];
  $name = $_SESSION['name'];
  $titul = $_SESSION['titul'];

echo $total, $right, $name, $titul;
*/
  $srcImg = __DIR__.DIRECTORY_SEPARATOR."img".DIRECTORY_SEPARATOR."ramka.png";
  $font=__DIR__.DIRECTORY_SEPARATOR."assets".DIRECTORY_SEPARATOR."albionic.ttf";
  $image = imageCreateFromPng($srcImg);

  echo $srcImg;

  if ($right == $total) {
    $text = "Поздравляю, $name \nВы успешно прошли тестирование \nи получаете звание $titul";
    $textColor=imagecolorallocate($image, 123, 104, 238);
  }
  else {
    $text = "Сожалеем, $name \n Тестирование не пройдено";
    $textColor=imagecolorallocate($image, 139, 0, 0);
  }

  echo $text;

  $backColor=imagecolorallocate($image, 200, 225, 225);
  imagettftext($image, 50, 0, 330, 280, $textColor, $font, $text);
  header('Content-type: image/png');
  imagePng($image);
  imagedestroy($image);

?>