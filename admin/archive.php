<?php
include_once 'start.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
  // Sanitize POST
  $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

  $allposts = $_POST['posts'];

  if(empty($allposts)){
    $allposts_err = 'Please choose post';
  }

  // Make sure errors are empty
  if(empty($allposts_err)){

    if ($allposts) {

      foreach ($allposts as $row){
        $stmt = $pdo->prepare("UPDATE `paragraph` SET `postid`=:postid WHERE `postid` = :id AND `item` = 'img'");
        $stmt->bindParam(':id', $row, PDO::PARAM_STR);
        $stmt->bindParam(':postid', $null, PDO::PARAM_STR);
        $stmt->execute();
        $stmt = $pdo->prepare("DELETE FROM `paragraph` WHERE `postid` = :id AND `item` != 'img'");
        $stmt->bindParam(':id', $row, PDO::PARAM_STR);
        $stmt->execute();
        $stmt = $pdo->prepare('DELETE FROM `comment` WHERE `postid` = :id');
        $stmt->bindParam(':id', $row, PDO::PARAM_STR);
        $stmt->execute();
        $stmt = $pdo->prepare('DELETE FROM `post` WHERE `id` = :id');
        $stmt->bindParam(':id', $row, PDO::PARAM_STR);
        $stmt->execute();
      }

      if($stmt->rowCount() > 0){
        $admintitle = "Deleted succesful";
      } else {
        die('Something went wrong');
      }
    }
  }
}

$sql = "
SELECT `post`.`id`, `post`.`date`, `post`.`userid`, `post`.`title`, `subject`.`id`, `subject`.`subject`, `users`.`id`, `users`.`name`, `users`.`users_image` FROM `post`
INNER JOIN `subject` ON `subject`.`id` = `post`.`subject`
INNER JOIN `users` ON `users`.`id` = `post`.`userid`
WHERE `post`.`userid` = :user_id
ORDER BY `post`.`id` DESC
";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $_SESSION['id'], PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_UNIQUE);
$total = $stmt->rowCount();

// Include head
include '../template/' . $template . '/header.php';
?>

    <!-- START CONTAINER -->
    <div class="container">

      <?php if($total > 0):?>
        <div class="inner">
          <h1><?php echo $admintitle; ?></h1>
          <?php if ($allposts_err):?>
            <span class="invalid-feedback"><?php echo $allposts_err; ?></span>
          <?php endif; ?>
          <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
            <table style="width:100%">

              <?php foreach($result as $i => $row):?>
                <tr>
                  <td><input type="checkbox" name="posts[]" value="<?php echo $i; ?>"></td>
                  <td>
                    <a href="../index.php?article=<?php echo $i;?>">
                      <?php echo $row['title'];?>
                    </a>
                  </td>
                  <td><?php echo $row['subject'];?></td>
                  <td><?php echo date("d.m.y", strtotime($row['date']));?></td>
                  <td>
                    <a href="../index.php?article=<?php echo $i;?>">
                      <?php echo file_get_contents(_BASE . '/img/icon/' . "edit.svg"); ?>
                    </a>
                  </td>
                </tr>
    	        <?php endforeach;?>

            </table>
            <button type="submit" value="Delete">Delete</button>
          </form>
        </div>

      <?php else:?>

      <?php
      include '../template/' . $template . '/error.php';
      ?>

      <?php endif;?>
    </div>
    <!-- END CONTAINER -->

    <?php
    include '../template/' . $template . '/footer.php';
    ?>
