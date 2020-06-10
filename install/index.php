<?php
// Include paths
include_once '../template/directory.php';
// Include meta vars
include_once '../array/meta.php';
// Include menu items
include_once '../array/links.php';

$server = $server_err = '';
$name = $name_err = '';
$username = $username_err = '';
$password = $password_err = '';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
  $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

  $server = trim($_POST['server']);
  $name = trim($_POST['name']);
  $username = trim($_POST['username']);
  $password = trim($_POST['password']);

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

    $data = "<?php\n";
    $data .= "define('DB_SERVER', '".$server."');\n";
    $data .= "define('DB_USERNAME', '".$username."');\n";
    $data .= "define('DB_PASSWORD', '".$password."');\n";
    $data .= "define('DB_NAME', '".$name."');\n\n";
    $data .= file_get_contents('db.txt');
    file_put_contents("../creds/db.php",$data) or die('ERROR:Can not write file');

    require_once '../creds/db.php';

    if($pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, 1)){

      $stmt = $pdo->prepare("SET foreign_key_checks=0");
      $stmt->execute();
      $sql = file_get_contents("blog.sql");
      $stmt = $pdo->prepare($sql);
      $stmt->execute();
      $stmt = $pdo->prepare("SET foreign_key_checks=1");
      if($stmt->execute()){
        $admintitle = "Install successful";
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

    <h1>Install</h1>

    <div class="inner">
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">

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
    </div>

  </div>
</div>

<?php
include '../template/footer.php';
?>
