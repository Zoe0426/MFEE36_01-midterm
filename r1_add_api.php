<?php
require './partsNOEDIT/connect-db.php';
// $output = [
//     'success' => false,
//     'postData' => $_POST, # 除錯用的
//     'code' => 0,
//     'error' => [],
// ];

$row = $_POST;




$sqlParent = "INSERT INTO `rest_info` (     
        `rest_name`,
        `catg_sid`,
        `rest_phone`,
        `rest_address`,
        `rest_info`,
        `rest_notice`,
        -- `rest_menu`,
        `rest_f_title`,
        `rest_f_ctnt`,
        -- `rest_f_img`,
        `date_start`,
        `date_end`,
        `m_start`,
        `m_end`,
        `e_start`,
        `e_end`,
        `n_start`,
        `n_end`,
        `p_max`,
        `pt_max`,
        -- `ml_time`,
        -- `weekly`,
        `created_at` ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,NOW())";


$stmt = $pdo->prepare($sqlParent);
$stmt->execute([
    $_POST['rest_name'],
    $_POST['catg_sid'],
    $_POST['rest_phone'],
    $_POST['rest_address'],
    $_POST['rest_info'],
    $_POST['rest_notice'],
    // $_POST['rest_menu'],
    $_POST['rest_f_title'],
    $_POST['rest_f_ctnt'],
    // $_POST['rest_f_img'],
    $_POST['date_start'],
    $_POST['date_end'],
    $_POST['m_start'],
    $_POST['m_end'],
    $_POST['e_start'],
    $_POST['e_end'],
    $_POST['n_start'],
    $_POST['n_end'],
    $_POST['p_max'],
    $_POST['pt_max'],
    // $_POST['ml_time'],
    // $_POST['weekly'],
]);

//要送進父資料表的資料
$parentSid = $pdo->lastInsertId(); //取得剛加入父表的品項編號 //echo $lastsid;



$sqlChild1 = "INSERT INTO rest_c_rr (`rest_sid`, `r_sid` )VALUES (?,?);";
$stm1 = $pdo->prepare($sqlChild1);

// INSERT INTO 子表格1 (父表格外部鍵, 欄位1, 欄位2, ...)
// VALUES (父表格的值, 值1, 值2, ...);

$data1 = [$_POST['r_sid']];

for ($i = 0; $i < count($data1); $i++) {
    $stm1->execute([
        $parentSid,
        $data[$i],
    ]);
}


$sqlChild2 = "INSERT INTO rest_c_rs (`rest_sid`, `s_sid` )VALUES (?,?);";
$stm2 = $pdo->prepare($sqlChild2);

$data2 = [$_POST['s_sid']];

for ($k = 0; $k < count($data2); $k++) {
    $stm2->execute([
        $parentSid,
        $data[$k],
    ]);
}

header('Content-Type: application/json');
echo json_encode($row, JSON_UNESCAPED_UNICODE);
