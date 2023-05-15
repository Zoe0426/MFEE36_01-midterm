<?php
require './partsNOEDIT/connect-db.php';


$cat_sid = isset($_GET['cat_sid']) ? $_GET['cat_sid'] : 'G';


//下拉的小類別列表
$sql_shopCatDet = sprintf("SELECT * FROM `shop_cat` WHERE `cat_sid`= '%s' ORDER BY `catDet_num` ", $cat_sid);
$r_shopCatDet = $pdo->query($sql_shopCatDet)->fetchAll();

header('Content-Type: application/json');
echo json_encode($r_shopCatDet, JSON_UNESCAPED_UNICODE);
