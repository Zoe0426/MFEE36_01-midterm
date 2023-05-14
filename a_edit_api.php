<?php
//require './parts/admin-required.php';
require './parts/connect-db.php';

$output = [
    'success' => false,
    'postData' => $_POST, # 除錯用的
    'code' => 0,
    'error' => [],
];


$sql1 = "SELECT `type_sid`, `type_name` FROM `act_type`";
$typeList = $pdo->query($sql1)->fetchAll();
echo print_r($typeList);

if (!empty($_POST['act_name']) and !empty($_POST['act_sid'])) {
    $isPass = true;
    # TODO: 檢查欄位資料

    $sqlParent = "UPDATE `act_info` SET 
    `type_sid`=?, 
    `act_name`=?, 
    `act_content`=?, 
    `act_policy`=?, 
    `act_city`=?, 
    `act_area`=?, 
    `act_address`=?, 
    `act_pic_sid`=?, 
    `act_pet_type`=?,
     `act_from`=?, 
     `post_status`=?,
    WHERE `act_sid`=? ";

    $stmt = $pdo->prepare($sqlParent);

    if ($isPass) {
        $stmt->execute([
            $_POST['type_sid'],
            $_POST['act_name'],
            $_POST['act_content'],
            $_POST['act_policy'],
            $_POST['act_city'],
            $_POST['act_area'],
            $_POST['act_address'],
            $_POST['act_pic_sid'],
            $_POST['act_pet_type'],
            $_POST['act_from'],
            $_POST['post_status'],
        ]);

        $parentSid = $pdo->lastInsertId();


        $sqlChild = "UPDATE `act_group` SET 
        `group_sid`=?,
        `act_sid`=?,
        `group_date`=?,
        `group_time`=?,
        `price_adult`=?,
        `price_kid`=?,
        `group_status`=?,
        `ppl_max`=?,
        `act_post_date`=NOW()
        WHERE `act_sid`= ?";

        $stmt->execute([
            $parentSid, //父表品項編號
            '2023-05-19',
            $time,
            $p_ad,
            $p_kid,
            1,
            $ppl_max
        ]);


        if (!!$stmt->rowCount()) { //如果表格新增成功，會是true，如果沒成功會是false
            $output['success'] = true;
            $output['message2'] = "子表格新增成功";
        }
    }
}
header('Content-Type: application/json');
echo json_encode($output, JSON_UNESCAPED_UNICODE);
