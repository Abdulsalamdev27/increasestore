<?php
setcookie("auth_token", "", time() - 3600, "/");

// Redirect with success flag
header("Location: login.php?logout=success");
exit;
