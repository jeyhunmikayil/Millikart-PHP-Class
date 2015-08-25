<?php
require_once("millikart.php");
$payment = new Millikart('Məbləği buraya daxil edin', 'Unikal dəyər/Reference code', 'Description');
$response = $payment->getURL();
header("Location: ".$response);
?>
