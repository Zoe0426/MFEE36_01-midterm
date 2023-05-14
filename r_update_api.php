<?php
require './partsNOEDIT/connect-db.php';
$output = [
    'success' => false, #更新成功或失敗的結果（MUST）
    'postData' => $_POST, # 除錯用的
    'code' => 0,
    'error' => [],
];


if (!empty($_POST['rest_name']) and !empty($_POST['rest_sid'])) { #若附合某些條件，則可以往下走

    $isPass = true;
    # TODO：檢查欄位資料,判斷格式錯，isPass設為false （MUST）
    # TODO：整理變數，轉換資料格式

    $sqlParent = "UPDATE `rest_info` 
    SET 
    `rest_name`=?,
    `catg_sid`=?,
    `rest_phone`=?,
    `rest_address`=?,
    `rest_info`=?,
    `rest_notice`=?,
    -- `rest_menu`=?,
    `rest_f_title`=?,
    `rest_f_ctnt`=?,
    -- `rest_f_img`=?,
    `date_start`=?,
    `date_end`=?,
    `m_start`=?,
    `m_end`=?,
    `e_start`=?,
    `e_end`=?,
    `n_start`=?,
    `n_end`=?,
    `p_max`=?,
    `pt_max`=?,
    -- `ml_time`=?,
    -- `weekly`=?,
    WHERE `rest_sid`= ? "; #(你的sql)

    $stmt = $pdo->prepare($sqlParent);

    if ($isPass) {
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

        $parentSid = $pdo->lastInsertId();


        //更新第一個子表格
        $data1 = $_POST['rest_svc'];
        print_r($data1);

        $sqlChild1 = "UPDATE rest_c_rs SET 
        `rest_sid`=?,
        `s_sid`=?
        WHERE `rest_sid`= ?";

        $stm1 = $pdo->prepare($sqlChild1);
        foreach ($data1 as $value) {
            $stm1->execute([
                $parentSid,
                $value,
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
            ]);
        }

        $output['success'] = !!$stmt->rowCount();
    } else {
        $output['error'] = "編輯失敗";
    }
}

header('Content-Type: application/json');
echo json_encode($output, JSON_UNESCAPED_UNICODE);
