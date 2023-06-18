<?php
require './partsNOEDIT/connect-db.php';

$output = [
    'success' => false,
    'postData' => $_POST,       #除錯用
    'code' => 0,
    'error' => ""
];

$supName = htmlentities($_POST['sup_name']);
$supMIW = htmlentities($_POST['sup_MIW']);
$supImg = htmlentities($_POST['sup_img']);

$sql_intoSup = "INSERT INTO `shop_sup`(
    `sup_name`, `sup_MIW`, `sup_img`
    ) VALUES (
    ?,?,?
    )";
$stmt_intoSup = $pdo->prepare($sql_intoSup);
$stmt_intoSup->execute([
    $supName,
    $supMIW,
    $supImg
]);
$output['success'] = !!$stmt_intoSup->rowCount();



header('Content-Type: application/json');
echo json_encode($output, JSON_UNESCAPED_UNICODE);
