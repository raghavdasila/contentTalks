<?php
session_id($_POST['key']);
session_start();
if(strcmp($_SESSION['set'],"set"))
  {
    session_destroy();
    header("Location: index.php?invalid=1");
  }
?>
