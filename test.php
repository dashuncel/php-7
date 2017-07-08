<?php
include_once __DIR__.DIRECTORY_SEPARATOR.'lib.php';

readDest($dest);
$jsonData=[];
$nom=1;
$results=[]; // массив с результатами
session_start();
var_dump($_SESSION);
// обработка get-запроса
if (! empty($_GET['test'])) {
  $result=array_search($_GET['test'].".json", $filelist, true);
  if ($result === false) {
    header($_SERVER['SERVER_PROTOCOL'].'404 Not Found'); 
    echo '<h2>Тест "'.$_GET['test'].'" не найден!</h2>';
    exit(); 
  } else {
    $data=file_get_contents($dest.$filelist[$result]);
    $jsonData=json_decode($data, JSON_UNESCAPED_UNICODE); 
    if (json_last_error() != 0) {
      echo "Ошибка чтения json файла ".json_last_error(); 
    }
  } 
  $nametest=$jsonData[0]['Название'];
  $titul=$jsonData[0]['титул'];
  $jsonData=$jsonData[0]['Вопросы'];
} else {
  header($_SERVER['SERVER_PROTOCOL'].'404 Not Found'); 
  echo '<h2>Cтраница не найдена!</h2>';
  exit();
}

// обработка post-запроса
if ($_POST) {
  
  echo "<pre>";
  print_r($_POST);
  echo "</pre>";
  
  echo "<pre>";
  print_r($_REQUEST);
  echo "</pre>";
  
  $right_answers=0;
  $total_questions=0;
  if (! empty($_POST['fio'])) {
    $name=$_POST['fio'];
  }
  foreach($jsonData as $key => $question) { // перебираем вопросы, есть ли ответ на вопрос?
    $total_questions++;
    if (! array_key_exists($key, $_POST)) {
      $results[]="$nom. Ответ не предоставлен. Правильный ответ: ".$jsonData[$key]['Ответ'];
      continue;
    }
    if (is_array($_POST[$key])) {
      $ans=implode($_POST[$key],","); // чекбоксы собираем из массива
    } else {
      $ans=$_POST[$key]; // радиокнопки - обычное значение
    }
    if ($jsonData[$key]['Ответ'] != $ans) {
      $results[]="$nom. Ответ неверный. Ваш ответ: $ans. Правильный ответ: ".$jsonData[$key]['Ответ'];
    } else {
      $results[]="$nom. Ответ $ans верный";
      $right_answers++;
    }
    $nom++;
  }
  $_SESSION['total'] = $total_questions;
  $_SESSION['right'] = $right_answers;
  $_SESSION['name'] = $name;
  $_SESSION['titul'] = $titul;

  echo '<div class="res"><pre>'.implode($results,"\n").'</pre></div>';
}

// заполнение формы в HTML
function fillForm() {
  global $jsonData;
  $answers=[];
  $r_answer='';
  $formStr='';

  foreach ($jsonData as $key => $question) {
    $name=$key;
    foreach ($question as $s_key => $qdata) {
      switch ($s_key) {
         case 'Вопрос':
           $question=$qdata;
         break;
         case 'Варианты':
           $answers=$qdata;
         break;
         case 'Ответ':
           $r_answer=$qdata;
         break;
      }    
    }
    $nom=++$key;
    $formStr.= "<label class=\"question\">$nom. $question</label>";
    $formStr.= "<ul class=\"answers\">";
    if (count(explode(",", $r_answer)) > 1) {
      foreach ($answers as $key => $ans) {
        $formStr.= "<li><input type=\"checkbox\" name=\"$name"."[]"."\" value=\"$key\" id=\"$name$key\"/><label for=\"$name$key\">$ans</label></li>";
      }  
    } else {
      foreach ($answers as $key => $ans) {
        $formStr.= "<li><input type=\"radio\" name=\"$name\" value=\"$key\" id=\"$name$key\"/><label for=\"$name$key\">$ans</label></li>";
      }
    }
    $formStr.= "</ul>";
  }  
  return $formStr;
}
?> 

<!DOCTYPE html>
<html>
<head>
  <title><?=$nametest?></title>
  <meta charset="utf-8">
  <link rel="stylesheet" href="gentest.css">
</head>
<body>
   <?php echo getMainMenu(); ?>
   <form>
   <fieldset class='hidden'>
      <legend>Результат</legend>
      <div class='output'></div>
   </fieldset>
   </form>
   <form action="" method="POST" enctype="application/json" class="mainform"> 
     <fieldset>
       <legend><?=$nametest?></legend>
       <label>Представьтесь, пожалуйста: <input type="text" name="fio" required></label><br/><br/>
       <?php echo fillForm(); ?>
       <br/><input type="submit" value="Проверить ответы" name="btn_check"><br/>
     </fieldset>
   </form>
   <script type="text/javascript">
     /* проверка формы на клиентской стороне */
   'use strict';
    const btn = document.querySelector("[type=submit]");
    const ans = document.querySelectorAll("[type=radio], [type=checkbox]");
    const quests=document.getElementsByClassName('question');
    const output=document.querySelector('div.output');

    btn.addEventListener('click', chkForm);
    Array.from(ans).forEach(a => {a.addEventListener('change',unsetErr)});

    //--проверка формы средствами JS:
    function chkForm(event) {
      event.preventDefault();
      const fldset=document.querySelector('fieldset.hidden');
      if (fldset) { fldset.classList.remove('hidden'); }
      Array.from(quests).forEach(quest => chkElement(quest)); // проверяем каждый вопрос, выбран ли ответ
      const errEl=document.getElementsByClassName('error');
      
      if (errEl.length > 0) {
        output.textContent = "Внимание! Не выбраны ответы для " + errEl.length + " вопр.(выделены красным цветом). Заполните всю форму.";
        event.preventDefault();
        return;
      } else { 
        const form=document.getElementsByClassName('mainform')[0];
        const xhr = new XMLHttpRequest();
        const formData = new FormData(form);
        xhr.open(form.getAttribute('method'), form.getAttribute('action'), false); // сделаем пока синхронный :)
        xhr.setRequestHeader('Content-Type', form.getAttribute('enctype'));
        xhr.send(formData);
      }

      if (Array.from(output.children).length == 0) {
        output.textContent='';
        const fragment = document.createDocumentFragment();
        const img = document.createElement('img');
        const br = document.createElement('br');
        const a = document.createElement('a');
        img.src='pick.php';
        a.setAttribute('target','_blank');
        a.textContent='';
        a.appendChild(img);
        fragment.appendChild(br);
        fragment.appendChild(a);
        output.appendChild(fragment);
      }
      const res=document.querySelector('.res');
      output.appendChild(res);
    }

    function unsetErr(event) {
      event.target.parentElement.parentElement.previousElementSibling.classList.remove('error');
    }

    function chkElement(quest) {
      const li=quest.nextElementSibling.firstChild.firstChild.getAttribute('name');
      const grp=document.getElementsByName(li);
      let chked=Array.from(grp).filter(g => { return g.checked; }); 
      if (chked.length == 0) {
        quest.classList.add('error');
      }
      else {
        quest.classList.remove('error');
      }
    }
   </script>
</body>
</html>