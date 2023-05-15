<?php

require './partsNOEDIT/connect-db.php';

$output = [
    'success' => false,
    'postData' => $_POST, # 除錯用的
    'code' => 0,
    'error' => [],
];

if (!empty($_POST['act_name']) and !empty($_POST['act_sid'])) {
    $isPass = true;

    # TODO: 檢查欄位資料
    if (empty($_POST['act_name'])) {
        $isPass = false;
        $output['error']['act_name'] = '必填！請輸入文字';
    }

    #TODO:整理變數，轉換資料格式

    $sqlParent = "UPDATE `act_info` SET 
        `type_sid`=?, 
        `act_name`=?, 
        `act_content`=?, 
        `act_policy`=?, 
        `act_city`=?, 
        `act_area`=?, 
        `act_address`=?
        WHERE `act_sid`=?";

    $stmtParent = $pdo->prepare($sqlParent);
    $parentSid = $_POST['act_sid'];
    $stmtParent->execute([
        $_POST['type_sid'],
        $_POST['act_name'],
        $_POST['act_content'],
        $_POST['act_policy'],
        $_POST['act_city'],
        $_POST['act_area'],
        $_POST['act_address'],
        $_POST['act_sid']
    ]);

    $sqlChild = "UPDATE `act_group` SET 
        `act_sid`=?, 
        `group_date`=?, 
        `group_time`=?, 
        `price_adult`=?, 
        `price_kid`=?,
        `ppl_max`=?
        WHERE `act_sid`=?";

    $stmtChild = $pdo->prepare($sqlChild);

    $stmtChild->execute([
        $parentSid,
        $_POST['group_date'],
        $_POST['group_time'],
        $_POST['price_adult'],
        $_POST['price_kid'],
        $_POST['ppl_max'],
        $parentSid
    ]);

    if ($stmtParent->rowCount() > 0 && $stmtChild->rowCount() > 0) {
        $output['success'] = true;
        $output['message'] = "表格更新成功";
    }
}

header('Content-Type: application/json');
echo json_encode($output, JSON_UNESCAPED_UNICODE);
