<?php

  if ($conf['use_mysqli']) {
  include($env['prefix']."/inc/func.dbase.php");
  } else {
  include($env['prefix']."/inc/func.dbase.mysql.php");
  }
  include($env['prefix']."/inc/func.misc.php");

?>
