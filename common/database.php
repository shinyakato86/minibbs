<?php
  try {
    $db = new PDO ('mysql:dbname=mini_bbs;host=localhost;charset=utf8','root','root');
  } catch (PDOException $e) {
      echo "DB接続に失敗しました。<br />";
      echo $e->getMessage();
      exit;
  }