<?php
include_once 'start.php';

  // Init vars
  $username = $useremail = $username_err = $useremail_err = '';

  if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

    $username = trim($_POST['username']);
    $useremail = trim($_POST['useremail']);

    if(empty($username)){
      $username_err = 'Please enter name';
    }
    if(empty($useremail)){
      $useremail_err = 'Please enter email';
    }

    $fileExtensions = ['jpeg','jpg','png','gif'];
    $fileName = $_FILES['userimage']['name'];
    $fileSize = $_FILES['userimage']['size'];
    $fileTmpName  = $_FILES['userimage']['tmp_name'];
    $fileType = $_FILES['userimage']['type'];
    $fileExtension = strtolower(end(explode('.',$fileName)));
    // Validate image
    if($fileName){
      if(!in_array($fileExtension,$fileExtensions)){
        $image_err = 'Please upload a JPG, PNG or GIF file';
      } elseif($fileSize > 2000000){
        $image_err = 'Please upload a file less than or equal to 2MB';
      } elseif(empty($image_err) && empty($username_err) && empty($useremail_err)) {
        list($width, $height) = getimagesize($fileTmpName);
        $src = imagecreatefromstring(file_get_contents($fileTmpName));
        $ratio = $width/$height;
        $new_width = ($width > 120) ? 120 : $width;
        $new_height = ($width > 120) ? 120/$ratio : $height;
        $uploadPath = $currentDir . '/img/users/' . basename($fileName);
        $dst = imagecreatetruecolor($new_width, $new_height);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        imagejpeg($dst, $uploadPath, 100);
        imagedestroy($dst);
        imagedestroy($src);
      }
    }

    if(empty($username_err) && empty($useremail_err)){
      $sql = 'UPDATE `users` SET `name`=:name, `email`=:email, `users_image`=:users_image WHERE `id` = :id';
      if($stmt = $pdo->prepare($sql)){
        ($fileName == '') ? $fileName = $_SESSION['users_image'] : $fileName;
        ($_POST['Submit'] == 'Delete') ? $fileName = '' : $fileName;
        session_start();
        $_SESSION['users_image'] = $fileName;
        $stmt->bindParam(':name', $username, PDO::PARAM_STR);
        $stmt->bindParam(':email', $useremail, PDO::PARAM_STR);
        $stmt->bindParam(':users_image', $fileName, PDO::PARAM_STR);
        $stmt->bindParam(':id', $_SESSION['id'], PDO::PARAM_STR);

        if($stmt->execute()){
          $admintitle = "Update successful";
        } else {
          die('Something went wrong');
        }
      }
    }
  }

  $sql = "
  SELECT * FROM `users`
  WHERE `users`.`id` = :id
  ";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':id', $_SESSION['id'], PDO::PARAM_INT);
  $stmt->execute();
  $user = $stmt->fetchAll();

  // Include head
  include '../template/header.php';
?>

<div class="container">
  <div class="inner">

    <h1><?php echo $admintitle; ?></h1>

    <div class="inner">
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">

        <div class="form-group">
          <label for="userimage">Image</label>
          <?php if ($user[0]['users_image']):?>
            <div class="users-image" style="background-image:url('../img/users/<?php print $user[0]['users_image']; ?>')"></div>
            <button name="Submit" type="submit" value="Delete">Delete image</button>
          <?php else: ?>
            <input type="file" name="userimage" accept="image/*" class="imageinput <?php echo (!empty($image_err)) ? 'is-invalid' : ''; ?>">
          <?php endif; ?>
          <?php if ($userimage_err):?>
            <span class="invalid-feedback"><?php print $userimage_err; ?></span>
          <?php endif; ?>
        </div>

        <div class="form-group">
          <label for="username">Name</label>
          <input name="username" type="text" value="<?php print $user[0]['name']; ?>">
          <?php if ($username_err):?>
            <span class="invalid-feedback"><?php print $username_err; ?></span>
          <?php endif; ?>
        </div>

        <div class="form-group">
          <label for="useremail">Email</label>
          <input name="useremail" type="email" value="<?php print $user[0]['email']; ?>">
          <?php if ($useremail_err):?>
            <span class="invalid-feedback"><?php print $useremail_err; ?></span>
          <?php endif; ?>
        </div>

        <div class="form-row">
          <div class="col">
            <button name="Submit" type="submit" value="Update">Update</button>
          </div>
        </div>

      </form>
    </div>

  </div>
</div>

<?php
include '../template/footer.php';
?>
