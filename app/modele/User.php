<?php
class User {
  private $id;
  private $username;
  private $password_hash;

  public function __construct($username, $password_hash) {
    $this->username = $username;
    $this->password_hash = $password_hash;
  }

  public function getId() {
    return $this->id;
  }

  public function getUsername() {
    return $this->username;
  }

  public function getPasswordHash() {
    return $this->password_hash;
  }

  public function setId($id) {
    $this->id = $id;
  }

  public function setUsername($username) {
    $this->username = $username;
  }

  public function setPasswordHash($password_hash) {
    $this->password_hash = $password_hash;
  }
}
?>
