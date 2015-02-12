<?php

$emailTo = $_GET["emailTo"];
$emailSubject = $_GET["emailSubject"];
$emailMessage = urldecode($_GET["emailMessage"]);

mail($emailTo, $emailSubject, $emailMessage);

?>