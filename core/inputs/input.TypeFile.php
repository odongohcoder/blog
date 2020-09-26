<input type="checkbox" id="deleteImage<?php echo $key; ?>" name="delete_paragraph[]" value="<?php echo $id; ?>">
<img src="img/article/<?php print $value; ?>">

<?php if($author): ?>
  <span class="editImageButton">
    <label for="deleteImage<?php echo $key; ?>" class="button">
      <?php print file_get_contents("img/icon/feather/trash-2.svg"); ?>
    </label>
    <label for="editImage<?php echo $key; ?>" class="button">
      <?php print file_get_contents("img/icon/feather/upload.svg"); ?>
    </label>
    <input type="file" id="editImage<?php echo $key; ?>" name="longcopy[<?php echo $key; ?>]" accept="image/*" class="imageinput <?php echo (!empty($image_err)) ? 'is-invalid' : ''; ?>">
  </span>
<?php endif; ?>

<input type="hidden" name="file_name[<?php echo $key; ?>]" value="<?php echo $value; ?>">
