<?php
// Include db config
require_once '../creds/db.php';
// Include paths
include_once '../array/directory.php';
// Include meta vars
include_once '../array/meta.php';
// Include menu items
include_once '../array/links.php';

  // Init vars
  $email = $password = '';
  $email_err = $password_err = '';

  // Process form when post submit
  if($_SERVER['REQUEST_METHOD'] === 'POST'){
    // Sanitize POST
    $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

    // Put post vars in regular vars
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validate email
    if(empty($email)){
      $email_err = 'Please enter email';
    }

    // Validate password
    if(empty($password)){
      $password_err = 'Please enter password';
    }

    // Make sure errors are empty
    if(empty($email_err) && empty($password_err)){
      // Prepare query
      $sql = 'SELECT * FROM users WHERE email = :email';

      // Prepare statement
      if($stmt = $pdo->prepare($sql)){
        // Bind params
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);

        // Attempt execute
        if($stmt->execute()){
          // Check if email exists
          if($stmt->rowCount() === 1){
            if($row = $stmt->fetch()){
              $hashed_password = $row['password'];
              if(password_verify($password, $hashed_password)){
                // SUCCESSFUL LOGIN
                session_start();
                $_SESSION['email'] = $email;
                $_SESSION['name'] = $row['name'];
                $_SESSION['id'] = $row['id'];
                $_SESSION['users_image'] = $row['users_image'];
                header('location: index.php');
              } else {
                // Display wrong password message
                $password_err = 'The password you entered is not valid';
              }
            }
          } else {
            $email_err = 'No account found for that email';
          }
        } else {
          die('Something went wrong');
        }
      }
    }
  }

// Include head
include '../template/header.php';
?>

  <div class="container first">
    <div class="wrapper">
      <div class="inner">

          <h1>Login</h1>
          <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <div class="inner">
            <div class="form-group">
              <label for="email">Email Address</label>
              <input type="email" name="email" class="<?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
              <?php if ($email_err):?>
                <span class="invalid-feedback"><?php echo $email_err; ?></span>
              <?php endif; ?>
            </div>
            <div class="form-group">
              <label for="password">Password</label>
              <input type="password" name="password" class="<?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
              <?php if ($password_err):?>
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
              <?php endif; ?>
            </div>
            <div class="form-row">
              <div class="col">
                <input type="submit" value="Login">
              </div>
              <div class="col">
                <a href="register.php">No account? Register</a>
              </div>
            </div>
            </div>
          </form>

        </div>
      </div>
    </div>

  <?php
  include '../template/footer.php';
  ?>
