<?php
require './partsNOEDIT/connect-db.php';



$sid = isset($_POST['product_sid']) ? $_POST['product_sid'] : "";

$sql_proUpdate = "UPDATE `shop_product` SET 
    `product_sid`=?,
    `category_sid`=?,
    `category_detail_sid`=?,
    `supplier_sid`=?,
    `for_pet_type`=?,
    `name`=?,
    `description`=?,
    `img`=?,
    `shelf_date`=?,
    `update_date`=NOW(),
    `shelf_status`=?
    WHERE `product_sid`=?";

$name = isset($_POST['name']) ? htmlentities($_POST['name']) : "";
$description = isset($_POST['description']) ? htmlentities($_POST['description']) : "";
$stmt_proUpdate = $pdo->prepare($sql_proUpdate);
$stmt_proUpdate->execute([
    $_POST['product_sid'],
    $_POST['category_sid'],
    $_POST['category_detail_sid'],
    $_POST['supplier_sid'],
    $_POST['for_pet_type'],
    $name,
    $description,
    $_POST['img'],
    $_POST['shelf_date'],
    '3',
    $_POST['product_sid']
]);


header("Location: s_list.php");
