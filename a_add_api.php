<?php
//連資料庫
require './partsNOEDIT/connect-db.php';

$output = [
    'success' => false,
    'postData' => $_POST, # 除錯用的
    'code' => 0,
    'error' => [],
];


//父輸入
$type = isset($_POST['type_sid']) ? $_POST['type_sid'] : 0;
$name = isset($_POST['act_name']) ? $_POST['act_name'] : "";
$content = isset($_POST['act_content']) ? $_POST['act_content'] : "";
$policy = isset($_POST['act_policy']) ? $_POST['act_policy'] : "";
$city = isset($_POST['act_city']) ? $_POST['act_city'] : "";
$area = isset($_POST['act_area']) ? $_POST['act_area'] : "";
$address = isset($_POST['act_address']) ? $_POST['act_address'] : "";


// //子輸入
$date = isset($_POST['group_date']) ? $_POST['group_date'] : 0;
$time = isset($_POST['group_time']) ? $_POST['group_time'] : 2;
$p_ad = isset($_POST['price_adult']) ? $_POST['price_adult'] : 0;
$p_kid = isset($_POST['price_kid']) ? $_POST['price_kid'] : 0;
$ppl_max = isset($_POST['ppl_max']) ? $_POST['ppl_max'] : 0;

$sqlParent = "INSERT INTO `act_info`(
    `type_sid`, `act_name`, `act_content`, 
    `act_policy`, `act_city`, `act_area`, 
    `act_address`, `act_pic_sid`, `act_pet_type`,
     `act_from`, `post_status`) 
     VALUES (?,?,?,
     ?,?,?,
     ?,?,?,
     ?,?)"; //加一筆資料到父表的SQL語法
$stmt = $pdo->prepare($sqlParent); //準備(父)
$stmt->execute([
    $type,
    $name,
    $content,
    $policy,
    $city,
    $area,
    $address,
    1,
    2,
    1,
    1
]);

if (!!$stmt->rowCount()) { //如果表格新增成功，會是true，如果沒成功會是false
    $output['success'] = true;
    $output['message'] = "父表格新增成功";
}


$parentSid = $pdo->lastInsertId(); //取得剛加入父表的品項編號 
//echo $lastsid;


//要加入子表的SQL語法
$sqlChild = "INSERT INTO `act_group`(
    `act_sid`, `group_date`, 
    `group_time`, `price_adult`, `price_kid`,
    `group_status`, `ppl_max`, `act_post_date`) 
    VALUES (
    ?,?,
    ?,?,?,
    ?,?,NOW());";
$stmt = $pdo->prepare($sqlChild); //準備(子)


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



header('Content-Type: application/json');
echo json_encode($output, JSON_UNESCAPED_UNICODE);
