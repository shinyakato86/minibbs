<?php

  session_start();
  require('../common/database.php');

  if (!empty($_POST)) {
    if ($_POST['name'] == '') {
      $error['name'] = 'blank';
    }
    if ($_POST['email'] == '') {
      $error['email'] = 'blank';
    }
    if ($_POST['name'] == '') {
      $error['name'] = 'blank';
    }
    if (strlen($_POST['password']) < 4) {
      $error['password'] = 'min_length';
    }
    if ($_POST['password'] == '') {
      $error['password'] = 'blank';
    }

    $fileName = $_FILES['image']['name'];
    if(!empty($fileName)) {
      $ext = substr($fileName, -3);
      if($ext != 'jpg' && $ext != 'gif' && $ext != 'png') {
        $error['image'] = 'type';
      }
    }

    if(empty($error)) {
      $user = $db->prepare('SELECT COUNT(*) AS cnt FROM users WHERE email=?');
      $user->execute(array($_POST['email']));
      $record = $user->fetch();
      if($record['cnt'] > 0) {
        $error['email'] = 'duplicate';
      }

    }

    if(empty($error)) {
      $image = date('YmdHis') . $_FILES['image']['name'];
      move_uploaded_file($_FILES['image']['tmp_name'],'../user_img/' . $image);
      $_SESSION['register'] = $_POST;
      $_SESSION['register']['image'] = $image;
      header('Location: check.php');
      exit();
    }

  }

  if($_REQUEST['action'] == 'rewrite' && isset($_SESSION['register'])) {
    $_POST = $_SESSION['register'];
  }

?>

<?php include ('../common/header.php'); ?>

<div class="contentsArea">
  <section class="section-01">
    <div class="heading02Wrap">
      <h2 class="heading02">会員登録</h2>
    </div>

    <div class="loginArea">
      <p class="mb-5">次のフォームに必要事項をご記入ください。</p>
      <form action="" method="post" enctype="multipart/form-data">

        <div class="form-group">
          <p class="mb-3 fw-bold"><label>名前<span class="icon_required">必須</span></label></p>
          <input class="input-01" type="text" name="name" size="35" maxlength="255" value="<?php echo htmlspecialchars($_POST['name']); ?>">

            <?php if ($error['name'] == 'blank'): ?>
              <p class="error">名前を入力してください。</p>
            <?php endif; ?>
        </div>

        <div class="form-group">
          <p class="mb-3 fw-bold"><label>メールアドレス<span class="icon_required">必須</span></label></p>

          <input class="input-01" type="email" name="email" size="35" maxlength="255" value="<?php echo htmlspecialchars($_POST['email']); ?>" />

          <?php if ($error['email'] == 'blank'): ?>
            <p class="error">メールアドレスを入力してください。</p>
          <?php endif; ?>

          <?php if ($error['email'] == 'duplicate'): ?>
            <p class="error">すでに登録されているメールアドレスです。</p>
          <?php endif; ?>
        </div>

        <div class="form-group">

          <p class="mb-3 fw-bold"><label>パスワード（4文字以上）<span class="icon_required">必須</span></label></p>
          <input class="input-01" type="password" name="password" size="10" maxlength="20" value="<?php echo htmlspecialchars($_POST['password']); ?>" />

          <?php if ($error['password'] && $error['password'] == 'blank'): ?>
            <p class="error">パスワードを入力してください。</p>
          <?php endif; ?>
          <?php if ($error['password'] && $error['password'] == 'min_length'): ?>
            <p class="error">パスワードを4文字以上で入力してください。</p>
          <?php endif; ?>

        </div>

        <div class="form-group">
          <p class="mb-3 fw-bold"><label>アイコン画像<span class="icon_option">任意</span></label></p>
          <input type="file" name="image" value="test">

          <?php if (isset($error['image']) && $error['image'] == 'type'): ?>
            <p class="error">画像は .jpg .png .gifを指定してください。</p>
          <?php endif; ?>
          <?php if (!empty($error)): ?>
            <p class="error">画像を再指定してください。</p>
          <?php endif; ?>
        </div>
        <div class="btnWrap mt-5"><input class="btn-01" type="submit" value="入力内容を確認する" /></div>
      </form>

    </div>
  </section>
</div>

<?php include ('../common/footer.php'); ?>
