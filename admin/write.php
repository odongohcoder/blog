<?php
include_once '../core/constants/directory.php';
include_once 'start.php';

  (!isset($_GET["subject"])) ? $subject_id = '0' : $subject_id = intval($_GET["subject"]);

  $sql = "SELECT `id`, `subject` FROM `subject`";
  $subjectList = Read_DB($pdo,$sql,null);
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

        <form action="../core/posts/post.article.php" method="POST" enctype="multipart/form-data">
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
