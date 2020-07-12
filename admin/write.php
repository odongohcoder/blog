<?php
include_once 'start.php';

  // Process form when post submit
  if($_SERVER['REQUEST_METHOD'] === 'POST'){
    // Sanitize POST
    $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

    // Put post vars in regular vars
    $title =  isset($_POST['title']) ? trim($_POST['title']) : '';
    $subtitle =  isset($_POST['subtitle']) ? trim($_POST['subtitle']) : '';
    $subject =  isset($_POST['subject']) ? trim($_POST['subject']) : '';

    // Create 2 dimensional arrays of files
    if (isset($_FILES['longcopy']['name'])) {
      $longcopy_files = $_FILES['longcopy']['name'];
      foreach ($longcopy_files as $key => $val){
        $longcopy_files[$key] = [];
        array_push($longcopy_files[$key], 'img', $val);
      }
    }
    // Create 2 dimensional arrays of textarea
    if (!empty($_POST['longcopy'])) {
      $longcopy_text = $_POST['longcopy'];
      foreach ($longcopy_text as $key => $val){
        // Validate longcopy
        (!empty($val)) ?: $longcopy_err = 'Please enter longcopy' ;
        $longcopy_text[$key] = [];
        $fileParameters = ['dl=0','dl=1'];
        $tmp = explode('?', $val);
        $fileParameter = strtolower(end($tmp));
        $fileExtensionsAudio = ['mp3','m4a','ogg'];
        $fileExtensionsImages = ['jpeg','jpg','png','gif'];
        $tmp = explode('.', strtok($val, "?"));
        $fileExtension = strtolower(end($tmp));
        if(in_array($fileExtension,$fileExtensionsAudio)){
          if(in_array($fileParameter,$fileParameters)){
            array_push($longcopy_text[$key], 'audio', strtok($val, "?") . '?dl=1');
          } else {
            array_push($longcopy_text[$key], 'audio', $val);
          }
        } elseif(in_array($fileExtension,$fileExtensionsImages)){
          array_push($longcopy_text[$key], 'img', $val);
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

    // Prepare file upload
  if($_FILES){
    $fileExtensions = ['jpeg','jpg','png','gif'];
    foreach ($_FILES['longcopy']['name'] as $i => $fileName){
      // Validate image
      (!empty($fileName)) ?: $image_err = 'Please upload image' ;
      //$fileName = $_FILES['longcopy']['name'][$i];
      $fileSize = $_FILES['longcopy']['size'][$i];
      $fileTmpName  = $_FILES['longcopy']['tmp_name'][$i];
      $fileType = $_FILES['longcopy']['type'][$i];
      $tmp = explode('.', $fileName);
      $fileExtension = strtolower(end($tmp));
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
            $uploadPath = _CURRENTDIR . _UPLOADDIRECTORY . $subfolder . basename($fileName);
            $dst = imagecreatetruecolor($new_width, $new_height);
            imagecopyresampled($dst, $src, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
            imagejpeg($dst, $uploadPath, 100);
            imagedestroy($dst);
          }
          imagedestroy($src);
        }
      }
    }
  }

    // Make sure errors are empty
    if(empty($title_err) && empty($subtitle_err) && empty($image_err) && empty($longcopy_err)){

      // Prepare insert query
      $sql = 'INSERT INTO `post` (`userid`, `title`, `subtitle`, `subject`, `date`) VALUES (:userid, :title, :subtitle, :subject, :datum)';

      if($stmt = $pdo->prepare($sql)){
        // Bind params
        $stmt->bindParam(':userid', $_SESSION['id'], PDO::PARAM_INT);
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':subtitle', $subtitle, PDO::PARAM_STR);
        $stmt->bindParam(':subject', $subject, PDO::PARAM_STR);
        $stmt->bindParam(':datum', $datum, PDO::PARAM_STR);
        $stmt->execute();
        $lastInsertId = $pdo->lastInsertId();

        if(!empty($longcopy)){
          foreach ($longcopy as $item => $row){
            $sql = 'INSERT INTO `paragraph` (`userid`, `paragraph`, `postid`, `item`) VALUES (:userid, :paragraph, :postid, :item)';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':userid', $_SESSION['id'], PDO::PARAM_STR);
            $stmt->bindParam(':paragraph', $row[1], PDO::PARAM_STR);
            $stmt->bindParam(':postid', $lastInsertId, PDO::PARAM_STR);
            $stmt->bindParam(':item', $row[0], PDO::PARAM_STR);
            $stmt->execute();
          }
        }

        if($row === end($longcopy) || empty($longcopy)){
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
?>

<!doctype html>
<html lang="nl">
<?php include '../template/' . $template . '/head.php'; ?>
<body>
  <input id="dark-mode" name="dark-mode" class="dark-mode-checkbox visually-hidden" type="checkbox">
  <!-- Start wrapper -->
  <div class="wrapper theme-container">
    <?php
    include '../template/' . $template . '/header.php';
    ?>

  <div class="container">
      <div class="inner">

        <h1><?php echo $admintitle; ?></h1>

          <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data" novalidate>
            <div id="AddPost">

              <div class="form-group">
                <label for="subject">Category</label>
                <div class="select-container">
                  <span class="select-arrow"></span>
                  <select name="subject">
                    <?php foreach($subjectList as $row):?>
                      <option value="<?php echo $row["id"]; ?>" <?php echo ($row["id"] == $subject_id) ? 'selected="selected"' : ''; ?>><?php echo $row["subject"]; ?></option>
                    <?php endforeach;?>
                  </select>
                </div>
                <?php if (!$subjectList):?>
                  <span class="invalid-feedback">Create category first</span>
                <?php endif; ?>
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

            <div class="form-group urlDIV">
              <label>Url</label>
              <input type="url" name="" class="urlinput <?php echo (!empty($url_err)) ? 'is-invalid' : ''; ?>">
              <?php if ($url_err):?>
                <span class="invalid-feedback"><?php echo $url_err; ?></span>
              <?php endif; ?>
            </div>

            </div>

            <button type="button" onclick="addTextField()">Add text</button><button type="button" onclick="addImageField()">Add image (upload)</button><button type="button" onclick="addUrlField()">Add url</button>

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
          function addUrlField() {
            var elmnt = document.getElementsByClassName('urlDIV')[0];
            var cln = elmnt.cloneNode(true);
            cln.classList.remove("urlDIV");
            cln.getElementsByClassName('urlinput')[0].value = "";
            cln.getElementsByClassName('urlinput')[0].setAttribute("name","longcopy[" + i + "]");
            document.getElementById('AddPost').appendChild(cln);
            ++i;
          }
          </script>

      </div>
    </div>

  </div>
  <!-- End wrapper -->

    <?php
    include '../template/' . $template . '/footer.php';
    ?>

    <?php
    unset($stmt);
    unset($pdo);
    ?>
  </body>
  </html>
