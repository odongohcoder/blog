<?php
$longcopy_err = '';
$image_err = '';
?>

<div id="AddedFields">

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

<button type="button" onclick="addTextField()">Add text</button>
<button type="button" onclick="addImageField()">Add image</button>

<script>
var i = document.querySelectorAll("input[name^='longcopy[']").length;
function addTextField() {
  var elmnt = document.getElementsByClassName('longcopyDIV')[0];
  var cln = elmnt.cloneNode(true);
  cln.classList.remove("longcopyDIV");
  cln.getElementsByClassName('textarea')[0].value = "";
  cln.getElementsByClassName('textarea')[0].setAttribute("name","longcopy[" + i + "]");
  cln.getElementsByClassName('textarea')[0].setAttribute("id","longcopy" + i);
  document.getElementById('AddedFields').appendChild(cln);
  ++i;
}
function addImageField() {
  var elmnt = document.getElementsByClassName('imageDIV')[0];
  var cln = elmnt.cloneNode(true);
  cln.classList.remove("imageDIV");
  cln.getElementsByClassName('imageinput')[0].value = "";
  cln.getElementsByClassName('imageinput')[0].setAttribute("name","longcopy[" + i + "]");
  document.getElementById('AddedFields').appendChild(cln);
  ++i;
}
</script>

<!-- <script>
tinymce.init({
  selector: '#longcopy0'
});
</script> -->
