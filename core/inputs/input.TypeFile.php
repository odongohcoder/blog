<img src="img/article/<?php print $value; ?>">

<?php if($author): ?>
  <span class="editImageButton">
    <button type="submit" value="Delete">
      <?php print file_get_contents("img/icon/feather/trash-2.svg"); ?>
    </button>
    <label for="editImage<?php echo $key; ?>" class="button">
      <?php print file_get_contents("img/icon/feather/upload.svg"); ?>
    </label>
    <input type="file" id="editImage<?php echo $key; ?>" name="longcopy[<?php echo $key; ?>]" accept="image/*" class="imageinput <?php echo (!empty($image_err)) ? 'is-invalid' : ''; ?>">
  </span>
<?php endif; ?>
