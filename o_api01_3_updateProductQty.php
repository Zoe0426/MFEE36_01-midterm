<?php
require './partsNOEDIT/connect-db.php';

$output = [
    'updateSuccess' => false, #修改資料成功或失敗的結果（MUST）
    'getData' => $_GET, # 除錯用的
    'code' => 0,
    'error' => [],

];
$rel_sid = isset($_GET['rel_sid']) ? $_GET['rel_sid'] : "";
$rel_seqNum_sid = isset($_GET['rel_seqNum_sid']) ? $_GET['rel_seqNum_sid'] : "";
$prodQty = isset($_GET['prodQty']) ? intval($_GET['prodQty']) : 0;

if (!empty($rel_sid) and !empty($rel_seqNum_sid) and !empty($prodQty)) {
    $sqlUpdateQty = "UPDATE `ord_cart` SET `prodQty`=? 
                     WHERE `rel_sid`=? AND `rel_seqNum_sid` =? ";

    $stm = $pdo->prepare($sqlUpdateQty);
    $stm->execute([
        $prodQty,
        $rel_sid,
        $rel_seqNum_sid
    ]);
    $output['updateSuccess'] = !!$stm->rowCount();
}

header('Content-Type: application/json');
echo json_encode($output, JSON_UNESCAPED_UNICODE);
