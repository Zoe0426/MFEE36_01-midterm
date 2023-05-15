<?php
require './partsNOEDIT/connect-db.php';


$sup_sid = isset($_GET['sup_sid']) ? $_GET['sup_sid'] : '1';


//下拉的小類別列表
$sql_shopSupMIW = sprintf("SELECT * FROM `shop_sup` WHERE `sup_sid`= '%s'", $sup_sid);
$r_shopSupMIW = $pdo->query($sql_shopSupMIW)->fetchAll();

header('Content-Type: application/json');
echo json_encode($r_shopSupMIW, JSON_UNESCAPED_UNICODE);
