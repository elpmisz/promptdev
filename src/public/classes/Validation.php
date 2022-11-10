<?php

namespace app\classes;

class Validation
{
  public function alert($alert, $text, $url)
  {
    $_SESSION['alert'] = $alert;
    $_SESSION['text']  = $text;
    exit(header("location: {$url}"));
  }

  public function input($data)
  {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

  public function int($data)
  {
    $data = filter_var($this->input($data), FILTER_VALIDATE_INT);
    return $data;
  }

  public function bool($data)
  {
    $data = filter_var($this->input($data), FILTER_VALIDATE_BOOLEAN);
    return $data;
  }

  public function url($data)
  {
    $data = filter_var($this->input($data), FILTER_VALIDATE_URL);
    return $data;
  }

  public function email($data)
  {
    $regex = "/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix";
    $data = strtolower($data);
    $data = filter_var($this->input($data), FILTER_SANITIZE_EMAIL);
    $data = preg_match($regex, $data);
    return $data;
  }

  public function password($data)
  {
    $data = preg_match("@[A-Z]@", $data); // Upper
    $data = preg_match("@[a-z]@", $data); // Lower
    $data = preg_match("@[0-9]@", $data); // Number
    $data = preg_match("@[^\w]@", $data); // Special
    return $data;
  }
}
