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
  function user($info) {
    return $info;
  }

}

class ArticleTest {
  public $subject;
  public $title;
  public $subtitle;
  public $author_name;
  public $author_image;
  public $date;

  public function __construct($result) {
    $this->subject = $result['subject'];
    $this->title = $result['title'];
    $this->subtitle = $result['subtitle'];
    $this->author_name = $result['name'];
    $this->author_image = $result['users_image'];
    $this->date = $result['date'];
  }

  function set_text() {
    global $author;
    $this->text = $value;
    $author ? include('admin/inputTypeText.php') : print $value;
  }

  public function author_name() {
      echo $this->author_name;
      echo $this->author_image;
  }

}
?>
