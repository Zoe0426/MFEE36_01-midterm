<?php
require './partsNOEDIT/connect-db.php';
$output = [
    'success' => false, #更新成功或失敗的結果（MUST）
    'postData' => $_POST, # 除錯用的
    'code' => 0,
    'error' => [],
];

// $rest_sid = isset($_POST['rest_sid']) ? intval($_POST['rest_sid']) : 0;



/*  -- `rest_menu`=?,
    -- `rest_f_img`=?,
    -- `ml_time`=?,
    -- `weekly`=?,*/

/* // $_POST['rest_menu'],
   // $_POST['rest_f_img'],
   // $_POST['ml_time'],
   // $_POST['weekly'],
   */

if (!empty($_POST['rest_name']) and !empty($_POST['rest_sid'])) {

    $isPass = true;
    $sqlParent = "UPDATE `rest_info` 
    SET 
    `rest_name`=?,
    `catg_sid`=?,
    `rest_phone`=?,

    `rest_address`=?,
    `rest_info`=?,
    `rest_notice`=?,

    `rest_f_title`=?,
    `rest_f_ctnt`=?,
    `date_start`=?,

    `date_end`=?,
    `m_start`=?,
    `m_end`=?,

    `e_start`=?,
    `e_end`=?,
    `n_start`=?,

    `n_end`=?,
    `p_max`=?,
    `pt_max`=?

 
    WHERE rest_sid= ? ";


    $stmt = $pdo->prepare($sqlParent);
    // $weeklyString = implode(',', $_POST['weekly']);

    $stmt->execute([
        $_POST['rest_name'],
        $_POST['catg_sid'],
        $_POST['rest_phone'],

        $_POST['rest_address'],
        $_POST['rest_info'],
        $_POST['rest_notice'],

        $_POST['rest_f_title'],
        $_POST['rest_f_ctnt'],
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


        $_POST['rest_sid'],
    ]);

    $output['parent'] = !!$stmt->rowCount();

    $parentSid = $_POST['rest_sid'];


    //更新第一個子表格
    $data1 = $_POST['rest_svc'];


    $sqlChild1 = "UPDATE rest_c_rs SET 
        `rest_sid`=?,
        `s_sid`=?
        WHERE `rest_sid`= ?";

    $stm1 = $pdo->prepare($sqlChild1);
    foreach ($data1 as $value) {
        $stm1->execute([
            $parentSid,
            $value,
            $parentSid
        ]);
    }

    //更新第二個子表格
    $data2 = $_POST['rest_rule'];

    $sqlChild2 = "UPDATE rest_c_rr SET 
        `rest_sid`=?,
        `r_sid`=?
        WHERE `rest_sid`= ?;";


    $stm2 = $pdo->prepare($sqlChild2);
    foreach ($data2 as $value) {
        $stm2->execute([
            $parentSid,
            $value,
            $parentSid
        ]);
    }
}
header('Content-Type: application/json');
echo json_encode($output, JSON_UNESCAPED_UNICODE);
