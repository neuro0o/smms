<?php
  session_start();
  include ("../../../../SMMS/CONFIG/config.php");

  if (isset($_SESSION["UID"])) {

      // unset session variables
      unset($_SESSION["UID"]);
      unset($_SESSION["userName"]);

      // destroy the session
      session_destroy();

      // redirect to the landing page with a success message
      header("Location: " . BASE_URL . "/index.php?logout=success");
      exit();
  }
?>