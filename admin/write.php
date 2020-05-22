<?php
include_once 'start.php';

  // Init vars
  $longcopy = $longcopy_files = $longcopy_text = [];
  $title = $subtitle = $subject = $date = '';
  $image_err = $title_err = $subtitle_err = $longcopy_err = $subject_err = '';

  // Process form when post submit
  if($_SERVER['REQUEST_METHOD'] === 'POST'){
    // Sanitize POST
    $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

    // Put post vars in regular vars
    $title =  trim($_POST['title']);
    $subtitle = trim($_POST['subtitle']);
    $subject = trim($_POST['subject']);

    // Create 2 dimensional arrays of files
    if ($_FILES['longcopy']['name']) {
      $longcopy_files = $_FILES['longcopy']['name'];
      foreach ($longcopy_files as $key => $val){
        $longcopy_files[$key] = [];
        array_push($longcopy_files[$key], 'img', $val);
      }
    }
    // Create 2 dimensional arrays of textarea
    if ($_POST['longcopy']) {
      $longcopy_text = $_POST['longcopy'];
      foreach ($longcopy_text as $key => $val){
        $longcopy_text[$key] = [];
        $fileParameters = ['dl=0','dl=1'];
        $fileParameter = strtolower(end(explode('?',$val)));
        $fileExtensions = ['mp3','m4a','ogg'];
        $fileExtension = strtolower(end(explode('.',strtok($val, "?"))));
        if(in_array($fileExtension,$fileExtensions)){
          if(in_array($fileParameter,$fileParameters)){
            array_push($longcopy_text[$key], 'audio', strtok($val, "?") . '?dl=1');
          } else {
            array_push($longcopy_text[$key], 'audio', $val);
          }
        } else {
          array_push($longcopy_text[$key], 'txt', $val);
        }
      }
    }
    // Combine 2 dimensional arrays and sort in order of appearance
    $longcopy = $longcopy_text + $longcopy_files;
    ksort($longcopy);

    // Validate title
    if(empty($title)){
      $title_err = 'Please enter title';
    }
    // Validate subtitle
    if(empty($subtitle)){
      $subtitle_err = 'Please enter subtitle';
    }
    // Validate longcopy
    if(empty($longcopy)){
      $longcopy_err = 'Please enter longcopy';
    }

    // Prepare file upload
    $fileExtensions = ['jpeg','jpg','png','gif'];
    foreach ($_FILES['longcopy']['name'] as $i => $fileName){
      //$fileName = $_FILES['longcopy']['name'][$i];
      $fileSize = $_FILES['longcopy']['size'][$i];
      $fileTmpName  = $_FILES['longcopy']['tmp_name'][$i];
      $fileType = $_FILES['longcopy']['type'][$i];
      $fileExtension = strtolower(end(explode('.',$fileName)));
      // Validate image
      if($fileName){
        if(!in_array($fileExtension,$fileExtensions)){
          $image_err = 'Please upload a JPG, PNG or GIF file';
        } elseif($fileSize > 2000000){
          $image_err = 'Please upload a file less than or equal to 2MB';
        } elseif(empty($image_err) && empty($title_err) && empty($subtitle_err) && empty($longcopy_err)){
          // Resize Image -- $maxDim defined in ../array/sizes.php included in start.php
          list($width, $height) = getimagesize($fileTmpName);
          $src = imagecreatefromstring(file_get_contents($fileTmpName));
          foreach ($maxDim as $keyDim => $valDim){
            $ratio = $width/$height;
            $new_width = ($width > $valDim) ? $valDim : $width;
            $new_height = ($width > $valDim) ? $valDim/$ratio : $height;
            $subfolder = ($keyDim == 'large') ? '' : $keyDim . '/';
            $uploadPath = $currentDir . $uploadDirectory . $subfolder . basename($fileName);
            $dst = imagecreatetruecolor($new_width, $new_height);
            imagecopyresampled($dst, $src, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
            imagejpeg($dst, $uploadPath, 100);
            imagedestroy($dst);
          }
          imagedestroy($src);
        }
      }
    }

    // Make sure errors are empty
    if(empty($image_err) && empty($title_err) && empty($subtitle_err) && empty($longcopy_err)){

      // Prepare insert query
      $sql = 'INSERT INTO post (`userid`, `title`, `subtitle`, `subject`, `date`) VALUES (:userid, :title, :subtitle, :subject, :date)';

      if($stmt = $pdo->prepare($sql)){
        // Bind params
        $stmt->bindParam(':userid', $_SESSION['id'], PDO::PARAM_INT);
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':subtitle', $subtitle, PDO::PARAM_STR);
        $stmt->bindParam(':subject', $subject, PDO::PARAM_STR);
        $stmt->bindParam(':date', date("Y-m-d"), PDO::PARAM_STR);
        $stmt->execute();
        $lastInsertId = $pdo->lastInsertId();
        foreach ($longcopy as $item => $row){
          $stmtTEXT = $pdo->prepare('INSERT INTO paragraph (`userid`, `paragraph`, `postid`, `item`) VALUES (:userid, :paragraph, :postid, :item)');
          $stmtTEXT->bindParam(':userid', $_SESSION['id'], PDO::PARAM_STR);
          $stmtTEXT->bindParam(':paragraph', $row[1], PDO::PARAM_STR);
          $stmtTEXT->bindParam(':postid', $lastInsertId, PDO::PARAM_STR);
          $stmtTEXT->bindParam(':item', $row[0], PDO::PARAM_STR);
          $stmtTEXT->execute();
        }
        // Attempt to execute
        if($stmtTEXT->rowCount() > 0){
          $admintitle = "Upload successful";
        } else {
          die('Something went wrong');
        }
      }
    }
  }

  (!isset($_GET["subject"])) ? $subject_id = '0' : $subject_id = intval($_GET["subject"]);

  $sql = "SELECT `id`, `subject` FROM `subject`";
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $subjectList = $stmt->fetchAll();
// Include head
include '../template/header.php';
?>

  <div class="container">
      <div class="inner">

        <h1><?php echo $admintitle; ?></h1>

          <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
            <div id="AddPost">

              <div class="form-group">
                <label for="subject">Subject</label>
                <div class="select-container">
                  <span class="select-arrow fa fa-chevron-down"></span>
                  <select name="subject">
                    <?php foreach($subjectList as $row):?>
                      <option value="<?php echo $row["id"]; ?>" <?php echo ($row["id"] == $subject_id) ? 'selected="selected"' : ''; ?>><?php echo $row["subject"]; ?></option>
                    <?php endforeach;?>
                  </select>
                </div>
              </div>

            <div class="form-group">
              <label>Title</label>
              <input type="text" name="title" class="<?php echo (!empty($title_err)) ? 'is-invalid' : ''; ?>">
              <?php if ($title_err):?>
                <span class="invalid-feedback"><?php echo $title_err; ?></span>
              <?php endif; ?>
            </div>

            <div class="form-group">
              <label>Subtitle</label>
              <input type="text" name="subtitle" class="<?php echo (!empty($subtitle_err)) ? 'is-invalid' : ''; ?>">
              <?php if ($subtitle_err):?>
                <span class="invalid-feedback"><?php echo $subtitle_err; ?></span>
              <?php endif; ?>
            </div>

            <div class="form-group imageDIV">
              <label>Image</label>
              <input type="file" name="" accept="image/*" class="imageinput <?php echo (!empty($image_err)) ? 'is-invalid' : ''; ?>">
              <?php if ($image_err):?>
                <span class="invalid-feedback"><?php echo $image_err; ?></span>
              <?php endif; ?>
            </div>

            <div class="form-group longcopyDIV">
              <label>Longcopy</label>
              <textarea name="" class="textarea <?php echo (!empty($longcopy_err)) ? 'is-invalid' : ''; ?>"></textarea>
              <?php if ($longcopy_err):?>
                <span class="invalid-feedback"><?php echo $longcopy_err; ?></span>
              <?php endif; ?>
            </div>

            </div>

            <button type="button" onclick="addTextField()">Add text</button><button type="button" onclick="addImageField()">Add image (upload)</button>

            <div class="form-row">
              <div class="col">
                <button type="submit" value="Add post">Add post</button>
              </div>
            </div>

          </form>

          <script>
          var i = 0;
          function addTextField() {
            var elmnt = document.getElementsByClassName('longcopyDIV')[0];
            var cln = elmnt.cloneNode(true);
            cln.classList.remove("longcopyDIV");
            cln.getElementsByClassName('textarea')[0].value = "";
            cln.getElementsByClassName('textarea')[0].setAttribute("name","longcopy[" + i + "]");
            document.getElementById('AddPost').appendChild(cln);
            ++i;
          }
          function addImageField() {
            var elmnt = document.getElementsByClassName('imageDIV')[0];
            var cln = elmnt.cloneNode(true);
            cln.classList.remove("imageDIV");
            cln.getElementsByClassName('imageinput')[0].value = "";
            cln.getElementsByClassName('imageinput')[0].setAttribute("name","longcopy[" + i + "]");
            document.getElementById('AddPost').appendChild(cln);
            ++i;
          }
          </script>

      </div>
    </div>

  <?php
  include '../template/footer.php';
  ?>
