<?php
session_start();
session_unset();
session_destroy();

// Redirect to homepage (or wherever you want)
header("Location: index.html");
exit;
?>
