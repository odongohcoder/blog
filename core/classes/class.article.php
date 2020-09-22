<?php
class Article {

  public function __construct($result) {
    $this->subject_name = $result['subject'];
    $this->subject_number = $result[5];
    $this->title = $result['title'];
    $this->subtitle = $result['subtitle'];
    $this->author_name = $result['name'];
    $this->author_image = $result['users_image'];
    $this->date = $result['date'];
  }

  function title() {
    $this->setText($this->title,'title');
  }

  function subtitle() {
    $this->setText($this->subtitle,'subtitle');
  }

  function subject() {
    global $subjects;
    $this->setSelect($subjects,$this->subject_number);
  }

  function setText($value,$name) {
    global $author;
    $author ? include('admin/inputTypeText.php') : print $value;
  }

  function setImage($key,$value) {
    global $author;
    include('admin/inputTypeFile.php');
  }

  function setSelect($list,$selected) {
    global $author;
    include('admin/inputTypeSelect.php');
  }

}
?>
