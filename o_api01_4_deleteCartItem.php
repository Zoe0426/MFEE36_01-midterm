<?php
require './partsNOEDIT/connect-db.php';

$output = [
    'deleteSuccess' => false, #修改資料成功或失敗的結果（MUST）
    'getData' => $_GET, # 除錯用的
    'code' => 0,
    'error' => [],
];
$rel_sid = isset($_GET['rel_sid']) ? $_GET['rel_sid'] : "";
$rel_seqNum_sid = isset($_GET['rel_seqNum_sid']) ? $_GET['rel_seqNum_sid'] : "";
$member_sid = isset($_GET['member_sid']) ? $_GET['member_sid'] : "";


$sqlDeleteItem = "UPDATE `ord_cart` SET `orderStatus`='003' 
                 WHERE `member_sid`=? 
                 AND `rel_sid`=? 
                 AND `rel_seqNum_sid`= ?;";

if (!empty($rel_sid) and !empty($rel_seqNum_sid) and !empty($member_sid)) {

    $stm = $pdo->prepare($sqlDeleteItem);
    $stm->execute([
        $member_sid,
        $rel_sid,
        $rel_seqNum_sid
    ]);
    $output['deleteSuccess'] = !!$stm->rowCount();
}

header('Content-Type: application/json');
echo json_encode($output, JSON_UNESCAPED_UNICODE);
