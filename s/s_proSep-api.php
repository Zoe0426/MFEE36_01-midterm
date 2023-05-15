<?php
require './partsNOEDIT/connect-db.php';


$spec_sid1 = isset($_GET['spec_sid1']) ? intval($_GET['spec_sid1']) : 1;


$sql_shopSpecDet = "";
//下拉的規格明細列表
if ($spec_sid1 == 1) {
    $sql_shopSpecDet = sprintf("SELECT * FROM `shop_spec` WHERE `spec_sid`= '%d' ORDER BY `specDet_sid` ", $spec_sid1);
} else {
    $sql_shopSpecDet = sprintf("SELECT * FROM `shop_spec` WHERE `spec_sid`= '%d' ORDER BY `specDet_name` ", $spec_sid1);
};

$r_shopSpecDet = $pdo->query($sql_shopSpecDet)->fetchAll();

header('Content-Type: application/json');
echo json_encode($r_shopSpecDet, JSON_UNESCAPED_UNICODE);
