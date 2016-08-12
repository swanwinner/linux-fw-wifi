<?php

  include("path.php");
  include("$env[prefix]/inc/common.login.php");


  $_SESSION['logined'] = false;

  header("Location: /");

  exit;

?>
