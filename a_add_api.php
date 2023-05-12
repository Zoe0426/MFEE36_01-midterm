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

//子輸入
$date = isset($_POST['group_date']) ? $_POST['group_date'] : "";
$time = isset($_POST['group_time']) ? $_POST['group_time'] : 2;
$p_ad = isset($_POST['price_adult']) ? $_POST['price_adult'] : 0;
$p_kid = isset($_POST['price_kid']) ? $_POST['price_kid'] : 0;
$ppl_max = isset($_POST['ppl_max']) ? $_POST['ppl_max'] : "";

$sqlParent = "INSERT INTO `act_info`(`type_sid`, `act_name`, `act_content`, `act_policy`, `act_city`, `act_area`, `act_address`) VALUES  (?,?,?,?,?,?,?)"; //加一筆資料到父表的SQL語法
$stm = $pdo->prepare($sqlParent); //準備(父)
$stm->execute([
    $type,
    $name,
    $content,
    $policy,
    $city,
    $area,
    $address
]);
if (!!$stmt->rowCount()) { //如果表格新增成功，會是true，如果沒成功會是false
    $output['success'] = true;
    $output['message'] = "父表格新增成功";
}


$parentSid = $pdo->lastInsertId(); //取得剛加入父表的品項編號 //echo $lastsid;


//要加入子表的SQL語法
$sqlChild = "INSERT INTO `act_group`(`act_sid`, `group_date`, `group_time`, `price_adult`, `price_kid`, `group_status`, `ppl_max`, `act_post_date`) VALUES  (?,?,?,?,?,?,?,NOW());";
$stm = $pdo->prepare($sqlChild); //準備(子)


$stm->execute([
    $parentSid, //父表品項編號
    $date,
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

// foreach ($data as $d) { // 使用for迴圈插入多條記錄
//     $stm->execute([
//         $parentSid, //父表品項編號
//         $d['xxx'],
//         $d['yyy'],
//         $d['zzz'],
//     ]);
// }

// $stm->execute([
//     $parentSid, //父表品項編號
//     $date,
//     $time,
//     $p_ad,
//     $p_kid,
//     '1',
//     $ppl_max
// ]);


// $data = [ //（從前端來的資料，依需求準備好子資料表內容）
//     ['date' => 'aaa',
//     'yyy' => "001",
//     'zzz' => '2023-05-07'],

//     ['xxx' => 'bbb',
//     'yyy' => "002",
//     'zzz' => '2023-06-07'],
// ];

// foreach ($data as $d) { // 使用for迴圈插入多條記錄
//     $stm->execute([
//         $parentSid, //父表品項編號
//         $d['xxx'],
//         $d['yyy'],
//         $d['zzz'],
//     ]);
// }
//===========簡約功能說明===========
//先新增一筆訂單到父表後，拿該筆訂單的編號，再到子表加入多筆對應的訂單明細

// 父表（order）

// |order_sid   |member_sid
// |ORD0005     |MEM001

// 子表 （order-detail）

// |order_sid   |order_detail_sid | order_detail_name
// |ORD0005     |001              |貓食-大罐
// |ORD0005     |002              |貓食-中罐
// |ORD0005     |003              |貓食-小罐

header('Content-Type: application/json');
echo json_encode($output, JSON_UNESCAPED_UNICODE);
