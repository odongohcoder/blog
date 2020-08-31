<?php
class Article {
  private $value;

  function set_text($value,$name) {
    global $author;
    $this->text = $value;
    $author ? include('admin/inputTypeText.php') : print $value;
  }
  function set_image($key,$value) {
    global $author;
    include('admin/inputTypeFile.php');
  }
  function set_select($list,$selected) {
    global $author;
    include('admin/inputTypeSelect.php');
  }

}
?>
