<?php
require './partsNOEDIT/connect-db.php';

$sql = "SELECT * FROM `mem_member` WHERE 1";
$stmt = $pdo->query($sql)->fetch();

print_r($stmt);
