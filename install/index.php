<?php
// Init session
session_start();
// Include paths
include_once '../array/directory.php';
// Include meta vars
include_once '../array/meta.php';
// Include menu items
include_once '../array/links.php';

$subfolder = '';
$server = $server_err = '';
$name = $name_err = '';
$username = $username_err = '';
$password = $password_err = '';
$form_success = '';
$admintitle = 'Install';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
  $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

  $subfolder = trim($_POST['subfolder']);
  $server = trim($_POST['server']);
  $name = trim($_POST['name']);
  $username = trim($_POST['username']);
  $password = trim($_POST['password']);

  (empty($subfolder)) ? $subfolder = '/' : $subfolder = '/'.$subfolder.'/';

  if(empty($server)){
    $server_err = 'Please enter server';
  }
  if(empty($name)){
    $name_err = 'Please enter database name';
  }
  if(empty($username)){
    $username_err = 'Please enter database username';
  }
  if(empty($password)){
    $password_err = 'Please enter database password';
  }

  if(empty($server_err) && empty($name_err) && empty($username_err) && empty($password_err)){

    $directory_data = "<?php\n";
    $directory_data .= "define('_SUBFOLDER', '".$subfolder."');\n";
    $directory_data .= file_get_contents('directory.txt');
    file_put_contents('../array/directory.php',$directory_data) or die('ERROR: Can not write directory file');

    $db_data = "<?php\n";
    $db_data .= "defined('_BASE') or die('Something went wrong');\n";
    $db_data .= "\n";
    $db_data .= "define('DB_SERVER', '".$server."');\n";
    $db_data .= "define('DB_USERNAME', '".$username."');\n";
    $db_data .= "define('DB_PASSWORD', '".$password."');\n";
    $db_data .= "define('DB_NAME', '".$name."');\n\n";
    $db_data .= file_get_contents('db.txt');
    file_put_contents("../creds/db.php",$db_data) or die('ERROR: Can not write db file');

    $_SESSION["token"] = bin2hex(random_bytes(16));

    require_once '../creds/db.php';

    if($pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, 1)){
      $stmt = $pdo->prepare("SET foreign_key_checks=0");
      $stmt->execute();
      $stmt = $pdo->prepare(file_get_contents("blog.sql"));
      $stmt->execute();
      $stmt = $pdo->prepare("SET foreign_key_checks=1");
      $stmt->execute();
      $sql = "UPDATE `settings` SET `token`= :token WHERE `id` = '1'";
      $stmt = $pdo->prepare($sql);
      $stmt->bindParam(':token', $_SESSION["token"], PDO::PARAM_STR);
      if($stmt->execute()){
        $admintitle = "Install successful";
        $form_success = true;
      } else {
        die('Something went wrong');
      }
    }
  }
}

// Include head
include '../template/header.php';
?>

<div class="container">
  <div class="inner">

    <h1><?php echo $admintitle; ?></h1>

    <div class="inner">

      <?php if ($form_success):?>
        <div class="col">
          <a class="button next" href="../admin/register.php?admin=<?php print $_SESSION["token"]; ?>">Register</a>
        </div>
      <?php else: ?>

      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">

        <div class="form-group">
          <label for="subfolder">Enter subfolder(s) if blog isn't main domain; like <i>blog</i> or <i>myfolder/blog</i></label>
          <input name="subfolder" type="text" value="">
        </div>

        <div class="form-group">
          <label for="server">Server</label>
          <input name="server" type="text" value="localhost">
          <?php if ($server_err):?>
            <span class="invalid-feedback"><?php print $server_err; ?></span>
          <?php endif; ?>
        </div>

        <div class="form-group">
          <label for="name">Database name</label>
          <input name="name" type="text" value="">
          <?php if ($name_err):?>
            <span class="invalid-feedback"><?php print $name_err; ?></span>
          <?php endif; ?>
        </div>

        <div class="form-group">
          <label for="username">Database username</label>
          <input name="username" type="text" value="">
          <?php if ($username_err):?>
            <span class="invalid-feedback"><?php print $username_err; ?></span>
          <?php endif; ?>
        </div>

        <div class="form-group">
          <label for="password">Database password</label>
          <input name="password" type="password" value="">
          <?php if ($password_err):?>
            <span class="invalid-feedback"><?php print $password_err; ?></span>
          <?php endif; ?>
        </div>

        <div class="form-row">
          <div class="col">
            <button name="Submit" type="submit" value="Install">Install</button>
          </div>
        </div>

      </form>
      <?php endif; ?>
    </div>

  </div>
</div>

<?php
include '../template/footer.php';
?>
