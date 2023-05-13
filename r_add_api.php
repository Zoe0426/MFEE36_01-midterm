<?php
require './partsNOEDIT/connect-db.php';

$output = [
    'success' => false, #新增資料成功或失敗的結果（MUST）
    'postData' => $_POST, # 除錯用的
    'code' => 0,
    'error' => [],
];


if (!empty($_POST['rest_name'])) { #若附合此條件，則可以往下走

    $isPass = true;
    # TODO：檢查欄位資料,判斷格式錯，isPass設為false （MUST）
    # TODO：整理變數，轉換資料格式

    $sql = "INSERT INTO `rest_info` (     
        `catg_sid`,
        `rest_name`,
        `rest_phone`,
        `rest_address`,
        `rest_info`,
        `rest_notice`,
        `rest_menu`,
        `rest_f_title`,
        `rest_f_ctnt`,
        `rest_f_img`,
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
        `ml_time`,
        `weekly`,
        `created_at` ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,NOW())";

    $stmt = $pdo->prepare($sql);

    if ($isPass) {
        $stmt->execute([
            $_POST['catg_sid'],
            $_POST['rest_name'],
            $_POST['rest_phone'],
            $_POST['rest_address'],
            $_POST['rest_info'],
            $_POST['rest_notice'],
            $_POST['rest_menu'],
            $_POST['rest_f_title'],
            $_POST['rest_f_title'],
            $_POST['rest_f_ctnt'],
            $_POST['rest_f_img'],
            $_POST['date_start'],
            $_POST['date_end'],
            $_POST['m_start'],
            $_POST['m_end'],
            $_POST['e_start'],
            $_POST['e_end'],
            $_POST['n_start'],
            $_POST['p_max'],
            $_POST['pt_max'],
            $_POST['ml_time'],
            $_POST['weekly'],
        ]);
        $output['success'] = !!$stmt->rowCount(); #若加成功，$output訊息的success會顯示true
    }
} else {
    $output['error'] = ""; #不符合條件
}


header('Content-Type: application/json');
echo json_encode($output, JSON_UNESCAPED_UNICODE);
