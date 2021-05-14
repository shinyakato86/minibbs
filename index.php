<?php
  session_start();
  require('./common/database.php');

  if(isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {

    $users = $db->prepare('SELECT * FROM users WHERE id=?');
    $users->execute(array($_SESSION['id']));
    $user = $users->fetch();
  } else {
    header('Location: login.php');
    exit();
  }

  if(!empty($_POST)) {
    if($_POST['message'] !== '') {
      $message = $db->prepare('INSERT INTO posts SET user_id=?, message=?, reply_id=?, created_at=NOW()');

      $message->execute(array(
        $user['id'],
        $_POST['message'],
        $_POST['reply_post_id']
      ));

      header('Location: index.php');
      exit();
    }
  }

  $page = $_REQUEST['page'];

  if($page == '') {
    $page = 1;
  }

  $page = max($page, 1);

  $counts = $db->query('SELECT COUNT(*) AS cnt FROM posts');
  $cnt = $counts->fetch();
  $maxPage = ceil($cnt['cnt'] / 5);
  $page = min($page, $maxPage);

  $start = ($page - 1) * 5;

  $posts = $db->prepare('SELECT m.name, m.picture, p.* FROM users m, posts p WHERE m.id=p.user_id ORDER BY p.created_at DESC LIMIT ?,5');

  $posts->bindParam(1, $start, PDO::PARAM_INT);
  $posts->execute();

  if(isset($_REQUEST['res'])) {
    $response = $db->prepare('SELECT m.name, m.picture, p.* FROM users m, posts p WHERE m.id=p.user_id AND p.id=?');
    $response->execute(array($_REQUEST['res']));
    $table = $response->fetch();
    $message = '@' . $table['name'] . ' ' . $table['message'];
  }

?>

<?php include ('./common/header.php'); ?>


<div class="contentsArea">
  <section class="section-01">
    <form action="" method="post">
      <dl>
        <dt class="mb-3"><?php echo htmlspecialchars($user['name']); ?>さん、メッセージを投稿してください。</dt>
        <dd>
          <textarea class="input-01" name="message" cols="50" rows="5"><?php echo htmlspecialchars($message); ?></textarea>
          <input type="hidden" name="reply_post_id" value="<?php echo htmlspecialchars($_REQUEST['res']); ?>">
        </dd>
      </dl>
      <div>
        <p>
          <input class="btn-01 mt-3" type="submit" value="投稿する">
        </p>
      </div>
    </form>

    <div class="mt-5">
      <?php foreach($posts as $post): ?>
        <div class="msgArea">
          <div class="msgArea_title">
            <p class="mb-1"><?php echo htmlspecialchars($post['id']); ?> <?php echo htmlspecialchars($post['name']); ?>さん<span class="ms-3"><?php echo htmlspecialchars($post['created_at']); ?></span>
          
            <?php if($_SESSION['id'] == $post['user_id']): ?>
              <a href="delete.php?id=<?php echo htmlspecialchars($post['id']); ?>"
              class="btn-02 ms-1">削除</a>
            <?php endif; ?>
            </p>
          </div>
          <div class="msgArea_box">
          <?php if(!empty($fileName)): ?>
            <p class="msgArea_box_img"><img class="msgArea_img" src="user_img/<?php echo htmlspecialchars($post['picture']); ?>" alt=""></p>
            <?php else: ?>
              <p class="msgArea_box_img"><img class="msgArea_img" src="user_img/noimage.jpg" alt=""></p>
          <?php endif; ?>

            <p class="msgArea_text ms-3"><?php echo htmlspecialchars($post['message']); ?><a href="index.php?res=<?php echo htmlspecialchars($post['id']); ?>"><span class="material-icons ms-1">reply</span></a></p>
          </div>
        </div>
      <?php endforeach; ?>

      <ul class="pagingArea mt-5">

        <?php if($page >  1): ?>
        <li class="pagingArea_item"><a href="index.php?page=<?php echo $page - 1 ?>">前のページへ</a></li>
        <?php else: ?>
          <li class="pagingArea_item">前のページへ</li>
        <?php endif; ?>

        <?php if($page <  $maxPage): ?>
        <li class="pagingArea_item"><a href="index.php?page=<?php echo $page + 1 ?>">次のページへ</a></li>
        <?php else: ?>
          <li class="pagingArea_item">次のページへ</li>
        <?php endif; ?>

      </ul>
    </div>
  </section>
</div>

<?php include ('./common/footer.php'); ?>
