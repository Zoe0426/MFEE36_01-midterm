<?php
require './partsNOEDIT/connect-db.php';



$sid = isset($_POST['pro_sid']) ? $_POST['pro_sid'] : "";

$sql_proUpdate = "UPDATE `shop_pro` SET 
    `pro_sid`=?,
    `cat_sid`=?,
    `catDet_sid`=?,
    `sup_sid`=?,
    `pro_for`=?,
    `pro_name`=?,
    `pro_describe`=?,
    `pro_img`=?,
    `pro_onWeb`=?,
    `pro_update`=NOW(),
    `pro_status`=?
    WHERE `pro_sid`=?";

$pro_name = isset($_POST['pro_name']) ? htmlentities($_POST['pro_name']) : "";
$pro_describe = isset($_POST['pro_describe']) ? htmlentities($_POST['pro_describe']) : "";
$stmt_proUpdate = $pdo->prepare($sql_proUpdate);
$stmt_proUpdate->execute([
    $_POST['pro_sid'],
    $_POST['cat_sid'],
    $_POST['catDet_sid'],
    $_POST['sup_sid'],
    $_POST['pro_for'],
    $pro_name,
    $pro_describe,
    $_POST['pro_img'],
    $_POST['pro_onWeb'],
    '3',
    $_POST['pro_sid']
]);


header("Location: s_list.php");
