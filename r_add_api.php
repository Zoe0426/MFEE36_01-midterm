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


header('Content-Type: application/json');
echo json_encode($output, JSON_UNESCAPED_UNICODE);
