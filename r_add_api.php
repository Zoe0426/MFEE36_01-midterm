<?php
require './partsNOEDIT/connect-db.php';
$output = [
    'success' => false,
    'postData' => $_POST,
    'error' => [],
];


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

$parentSid = $pdo->lastInsertId(); //取得剛加入父表的品項編號 //echo $lastsid;



$data1 = $_POST['rest_svc'];
print_r($data1);

$sqlChild1 = "INSERT INTO rest_c_rs (`rest_sid`, `s_sid` ) VALUES (?, ?);";
$stm1 = $pdo->prepare($sqlChild1);
foreach ($data1 as $value) {
    $stm1->execute([
        $parentSid,
        $value,
    ]);
}

$data2 = $_POST['rest_rule'];

$sqlChild2 = "INSERT INTO rest_c_rr (`rest_sid`, `r_sid` )VALUES (?,?);";
$stm2 = $pdo->prepare($sqlChild2);
foreach ($data2 as $value) {
    $stm2->execute([
        $parentSid,
        $value,
    ]);
}

header('Content-Type: application/json');
echo json_encode($output, JSON_UNESCAPED_UNICODE);
