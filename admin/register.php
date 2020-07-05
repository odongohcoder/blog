<?php
// Init session
session_start();
// Include paths
include_once '../array/directory.php';
// Include db config
require_once '../creds/db.php';
// Include meta vars
include_once '../array/meta.php';
// Include menu items
include_once '../array/links.php';

if (isset($_SESSION["token"])) {
  $stmt = $pdo->prepare("SELECT `token` FROM `settings` WHERE `id` = '1'");
  $stmt->execute();
  $token = $stmt->fetchAll();
}
  // Init vars
  $name = $email = $password = $confirm_password = '';
  $name_err = $email_err = $password_err = $confirm_password_err = '';
  $admin = isset($_SESSION["token"]) ? ($token[0]["token"] == $_SESSION["token"] ? '1' : '0') : '0';
  $admintitle = isset($_SESSION["token"]) ? 'Register administrator' : 'Register';

  // Process form when post submit
  if($_SERVER['REQUEST_METHOD'] === 'POST'){
    // Sanitize POST
    $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

    // Put post vars in regular vars
    $name =  trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validate email
    if(empty($email)){
      $email_err = 'Please enter email';
    } else {
      $sql = 'SELECT id FROM users WHERE email = :email';
      if($stmt = $pdo->prepare($sql)){
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        if($stmt->execute()){
          if($stmt->rowCount() === 1){
            $email_err = 'Email is already taken';
          }
        } else {
          die('Something went wrong');
        }
      }
      unset($stmt);
    }

    // Validate name
    if(empty($name)){
      $name_err = 'Please enter name';
    }

    // Validate password
    if(empty($password)){
      $password_err = 'Please enter password';
    } elseif(strlen($password) < 6){
      $password_err = 'Password must be at least 6 characters ';
    }

    // Validate Confirm password
    if(empty($confirm_password)){
      $confirm_password_err = 'Please confirm password';
    } else {
      if($password !== $confirm_password){
        $confirm_password_err = 'Passwords do not match';
      }
    }

    // Make sure errors are empty
    if(empty($name_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)){
      // Hash password
      $password = password_hash($password, PASSWORD_DEFAULT);

      // Prepare insert query
      $sql = 'INSERT INTO `users` (`name`, `email`, `password`, `admin`) VALUES (:name, :email, :password, :admin)';

      if($stmt = $pdo->prepare($sql)){

        print $admin;
        // Bind params
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        $stmt->bindParam(':admin', $admin, PDO::PARAM_BOOL);

        // Attempt to execute
        if($stmt->execute()){
          // Redirect to login
          header('location: login.php');
        } else {
          die('Something went wrong');
        }
      }
    }
  }

  include '../template/' . $template . '/header.php';
?>

  <div class="container">
    <div class="wrapper">
      <div class="inner">

          <h1><?php echo $admintitle; ?></h1>

          <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <div class="form-group">
              <label for="name">Name</label>
              <input type="text" name="name" class="form-control form-control-lg <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
              <?php if ($name_err):?>
                <span class="invalid-feedback"><?php echo $name_err; ?></span>
              <?php endif; ?>
            </div>
            <div class="form-group">
              <label for="email">Email Address</label>
              <input type="email" name="email" class="form-control form-control-lg <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
              <?php if ($email_err):?>
                <span class="invalid-feedback"><?php echo $email_err; ?></span>
              <?php endif; ?>
            </div>
            <div class="form-group">
              <label for="password">Password</label>
              <input type="password" name="password" class="form-control form-control-lg <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
              <?php if ($password_err):?>
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
              <?php endif; ?>
            </div>
            <div class="form-group">
              <label for="confirm_password">Confirm Password</label>
              <input type="password" name="confirm_password" class="form-control form-control-lg <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
              <?php if ($confirm_password_err):?>
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
              <?php endif; ?>
            </div>

            <div class="form-row">
              <div class="col">
                <input type="submit" value="Register" class="btn btn-success btn-block">
              </div>
              <div class="col">
                <a href="login.php" class="btn btn-light btn-block">Have an account? Login</a>
              </div>
            </div>
          </form>

      </div>
    </div>
  </div>

  <?php
  include '../template/' . $template . '/footer.php';
  ?>
