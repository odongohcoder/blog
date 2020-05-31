<?php
include_once 'start.php';

  if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);

    if(empty($name)){
      $name_err = 'Please enter name';
    }
    if(empty($email)){
      $email_err = 'Please enter email';
    }

  if($_FILES){
    $fileExtensions = ['jpeg','jpg','png','gif'];
    $fileName = $_FILES['userimage']['name'];
    $fileSize = $_FILES['userimage']['size'];
    $fileTmpName  = $_FILES['userimage']['tmp_name'];
    $fileType = $_FILES['userimage']['type'];
    $tmp = explode('.', $fileName);
    $fileExtension = strtolower(end($tmp));
    // Validate image
    if($fileName){
      if(!in_array($fileExtension,$fileExtensions)){
        $image_err = 'Please upload a JPG, PNG or GIF file';
      } elseif($fileSize > 2000000){
        $image_err = 'Please upload a file less than or equal to 2MB';
      } elseif(empty($image_err) && empty($name_err) && empty($email_err)) {
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
  }

    if(empty($name_err) && empty($email_err)){
      $sql = 'UPDATE `users` SET `name`=:name, `email`=:email, `users_image`=:users_image WHERE `id` = :id';
      if($stmt = $pdo->prepare($sql)){
        ($fileName == '') ? $fileName = $_SESSION['users_image'] : $fileName;
        ($_POST['Submit'] == 'Delete') ? $fileName = '' : $fileName;
        $_SESSION['users_image'] = $fileName;
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
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
          <?php if ($image_err):?>
            <span class="invalid-feedback"><?php print $image_err; ?></span>
          <?php endif; ?>
        </div>

        <div class="form-group">
          <label for="name">Name</label>
          <input name="name" type="text" value="<?php print $user[0]['name']; ?>">
          <?php if ($name_err):?>
            <span class="invalid-feedback"><?php print $name_err; ?></span>
          <?php endif; ?>
        </div>

        <div class="form-group">
          <label for="email">Email</label>
          <input name="email" type="email" value="<?php print $user[0]['email']; ?>">
          <?php if ($email_err):?>
            <span class="invalid-feedback"><?php print $email_err; ?></span>
          <?php endif; ?>
        </div>

        <div class="form-row">
          <div class="col">
            <button name="Submit" type="submit" value="Update">Update</button>
          </div>
        </div>

      </form>
    </div>
    <?php include_once 'setting.php'; ?>

  </div>
</div>

<?php
include '../template/footer.php';
?>
