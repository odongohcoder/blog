<?php if($author): ?>
  <div class="select-container">
    <span class="select-arrow"></span>
    <select name="subject">
      <?php foreach($list as $option):?>
        <option value="<?php echo $option[0]; ?>" <?php echo ($option[0] == $selected) ? 'selected="selected"' : ''; ?>><?php echo $option[1]; ?></option>
      <?php endforeach;?>
    </select>
  </div>
<?php else: ?>
  <?php foreach($list as $option):?>
    <?php if($option[0] == $selected):?>
      <a href="index.php?subject=<?php echo $option[0];?>" class="subject"><?php echo $option[1];?></a>
    <?php endif; ?>
  <?php endforeach;?>
<?php endif; ?>
