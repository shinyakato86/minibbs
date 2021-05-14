<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
<meta name="format-detection" content="telephone=no">
<title>ひとこと掲示板</title>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
<link rel="stylesheet" href="/minibbs/css/style.css">
</head>

<body>

<header class="header">

  <div class="header_inner">
    
    <p class="headerLogo"><a href="/minibbs/"><span class="header_title">ひとこと掲示板</span></a></p>

    <?php if(isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()): ?>
    <div style="text-align: right"><a href="logout.php" class="btn-03">ログアウト</a></div>
    <?php else: ?>
      <div style="text-align: right"><a href="/minibbs/login.php" class="btn-03">ログイン</a></div>
    <?php endif; ?>

  </div>

</header>