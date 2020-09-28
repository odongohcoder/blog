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
    $this->setText($this->title,'title',null);
  }

  function subtitle() {
    $this->setText($this->subtitle,'subtitle',null);
  }

  function subject() {
    global $subjects;
    $this->setSelect($subjects,$this->subject_number);
  }

  function authorImage() {
    return $this->author_image;
  }

  function authorName() {
    return $this->author_name;
  }

  function date() {
    return $this->date;
  }

  function setText($value,$name,$id) {
    global $author;
    $author ? include('core/inputs/input.TypeText.php') : print $value;
  }

  function setImage($value,$key,$id) {
    global $author;
    include('core/inputs/input.TypeFile.php');
  }

  function setSelect($list,$selected) {
    global $author;
    include('core/inputs/input.TypeSelect.php');
  }

}
?>
