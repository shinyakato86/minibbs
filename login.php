<?php

  session_start();
  require('./common/database.php');

  if($_COOKIE['email'] !==  '') {
    $email = $_COOKIE['email'];
  }

  if(!empty($_POST)) {
    $email = $_POST['email'];

    if($_POST['email'] !== '' && $_POST['password'] !== '') {
      $login = $db->prepare('SELECT * FROM users WHERE email=? AND password=?');

    $login->execute(array(
      $_POST['email'],
      sha1($_POST['password'])
    ));

    $user = $login->fetch();

    if($user) {
      $_SESSION['id'] = $user['id'];
      $_SESSION['time'] = time();

      if($_POST['save'] == 'on') {
        setcookie('email', $_POST['email'], time()+60*60*24*14);
      }
      header('Location: index.php');
      exit();
    } else {
        $error['login'] = 'failed';
      }
    } else {
        $error['login'] = 'blank';
    }
  }

?>

<?php include ('./common/header.php'); ?>

<div class="contentsArea">
  <section class="section-01">

    <div class="heading02Wrap">
      <h2 class="heading02">ログイン</h2>
    </div>

      <div class="loginArea">
        <p class="text-end mb-3"><a href="join/">新規会員登録はこちら</a></p>
        <form action="" method="post">
          <div class="form-group">
            <input class="input-01" placeholder="メールアドレス" type="text" name="email" size="35" maxlength="255" value="<?php echo htmlspecialchars($email); ?>" />

              <?php if ($error['login'] == 'blank'): ?>
                <p class="error">メールアドレスとパスワードを入力してください。</p>
              <?php endif; ?>

              <?php if ($error['login'] == 'failed'): ?>
                <p class="error">ログインに失敗しました。</p>
              <?php endif; ?>
          </div>
          <div class="form-group">
            <input class="input-01" placeholder="パスワード" type="password" name="password" size="35" maxlength="255" value="<?php echo htmlspecialchars($_POST['password']); ?>" />
          </div>
          <div class="checkbox">
              <label class="d-flex align-items-center mt-3">
                <input class="input-02" name="remember" type="checkbox" value="on" name="save">次回からは自動的にログインする</label>
            </div>
            <div class="btnWrap">
              <input class="btn-01 mt-5" type="submit" value="ログイン">
            </div>
        </form>
      </div>

  </section>
</div>




<?php include ('./common/footer.php'); ?>


