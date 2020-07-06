<?php
include_once 'start.php';

  // Process form when post submit
  if($_SERVER['REQUEST_METHOD'] === 'POST'){
    // Sanitize POST
    $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

    // Put post vars in regular vars
    $subject =  isset($_POST['subject']) ? trim($_POST['subject']) : '';
    $allsubjects = isset($_POST['subjects']) ? $_POST['subjects'] : [];

    // Validate subject
    if(empty($subject) && empty($allsubjects)){
      $subject_err = 'Please choose or enter subject';
    }

    // Make sure errors are empty
    if(empty($subject_err)){

      // Prepare insert query
      $sql = 'INSERT INTO subject (`subject`) VALUES (:subject)';

      if($stmt = $pdo->prepare($sql)){

        // Bind params
        if ($allsubjects) {
          foreach ($allsubjects as $row){
            $stmtSUBJECT = $pdo->prepare('DELETE FROM `subject` WHERE `id` = :id');
            $stmtSUBJECT->bindParam(':id', $row, PDO::PARAM_STR);
            $stmtSUBJECT->execute();
            if($stmtSUBJECT->errorInfo()[1] == '1451'){

            }
          }
        }
        if ($subject) {
          $stmt->bindParam(':subject', $subject, PDO::PARAM_STR);
          $stmt->execute();
        }

        // Attempt to execute
        if($stmt->rowCount() > 0 || $stmtSUBJECT->rowCount() > 0){
          $admintitle = "Updated successful";
        } else {
          die('Something went wrong');
        }
      }
    }
  }

  $sql = "SELECT `id`, `name`, `email` FROM `users` WHERE `email`=:email";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(['email' => $_SESSION['email']]);
  $userid = $stmt->fetch();
  $sql = "SELECT `id`, `subject` FROM `subject`";
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $subjectList = $stmt->fetchAll();

  // Include head
  include '../template/' . $template . '/header.php';
?>

  <div class="container">
      <div class="inner">

        <h1><?php echo $admintitle; ?></h1>

          <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">

            <div class="form-group">
              <input type="text" name="subject" class="<?php echo (!empty($subject_err)) ? 'is-invalid' : ''; ?>">
              <?php if ($subject_err):?>
                <span class="invalid-feedback"><?php echo $subject_err; ?></span>
              <?php endif; ?>
            </div>

            <button type="submit" value="Update">Add</button>

            <?php if ($subjectList):?>
            <table style="width:100%">
              <?php foreach($subjectList as $row):?>
              <tr>
                <td>
                  <label>
                    <input type="checkbox" name="subjects[]" value="<?php echo $row['id']; ?>">
                    <?php echo $row["subject"]; ?>
                  </label>
                </td>
              </tr>
              <?php endforeach;?>
            </table>

            <button type="submit" value="Update">Delete</button>
            <?php endif; ?>

          </form>

      </div>
    </div>

  <?php
  include '../template/' . $template . '/footer.php';
  ?>
