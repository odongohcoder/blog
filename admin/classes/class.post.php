<?php
class Post {
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

}
?>
