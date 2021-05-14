<?php
  session_start();
  require('../common/database.php');

  if(!isset($_SESSION['register'])) {
    header('Location: index.php');
    exit();
  }

  if(!empty($_POST)) {
    $statement = $db->prepare('INSERT INTO users SET name=?, email=?, password=?, picture=?, created_at=NOW()');

    $statement->execute(array(
      $_SESSION['register']['name'],
      $_SESSION['register']['email'],
      sha1($_SESSION['register']['password']),
      $_SESSION['register']['image']
    ));

    unset($_SESSION['register']);

    header('Location: thanks.php');
    exit() ;
  }

?>

<?php include ('../common/header.php'); ?>

<div class="contentsArea">
  <section class="section-01">
    <div class="heading02Wrap">
      <h2 class="heading02">会員登録</h2>
    </div>

    <div class="loginArea">
      <p class="mb-5">以下の内容でよろしいでしょうか。</p>

      <form action="" method="post">
        <input type="hidden" name="action" value="submit" />

        <div class="form-group">
          <p class="mb-3 fw-bold"><label>名前<span class="icon_required">必須</span></label></p>
          <?php echo htmlspecialchars($_SESSION['register']['name']); ?>
        </div>

        <div class="form-group">
          <p class="mb-3 fw-bold"><label>メールアドレス<span class="icon_required">必須</span></label></p>
          <?php echo htmlspecialchars($_SESSION['register']['email']); ?>
        </div>
        
        <div class="form-group">
          <p class="mb-3 fw-bold"><label>パスワード<span class="icon_required">必須</span></label></p>
          <p>【表示されません】<p>
        </div>

        <div class="form-group">
          <p class="mb-3 fw-bold"><label>アイコン画像</label></p>
          <?php if($_SESSION['register']['image'] !== ''): ?>
              <img src="../user_img/<?php echo htmlspecialchars($_SESSION['register']['image']); ?>" alt="">
          <?php endif; ?>
        </div>

        <div class="mt-5 btnArea">
          <a class="btn-03" href="index.php?action=rewrite">戻る</a>
          <input class="btn-01 ms-5" type="submit" value="登録する" />
        </div>
      </form>

    </div>
  </section>
</div>

<?php include ('../common/footer.php'); ?>
