<?php
require './partsNOEDIT/connect-db.php';
$output = [
    'success' => false,
    'postData' => $_POST,
    'code' => 0,
    'error' => [],
];

if (!empty($_POST['rest_name'])) {
    $dateEnd =  $_POST['date_end'];

    $isPass = true;
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
        `created_at` ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,NOW())";


    $stmt = $pdo->prepare($sqlParent);
    $weeklyString = implode(',', $_POST['weekly']);

    if (empty($dateEnd)) {
        $dateEnd = NULL;
    }

    $m_end =  $_POST['m_end'];
    if (empty($m_end)) {
        $m_end = NULL;
    }

    $e_start = $_POST['e_start'];
    if (empty($e_start)) {
        $e_start = NULL;
    }

    $e_end = $_POST['e_end'];
    if (empty($e_end)) {
        $e_end = NULL;
    }

    $n_start = $_POST['n_start'];
    if (empty($n_start)) {
        $n_start = NULL;
    }


    $isPass = true;

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
        $_POST['rest_f_img'],
        $_POST['date_start'],
        $dateEnd,
        $_POST['m_start'],
        $m_end,
        $e_start,
        $e_end,
        $n_start,
        $_POST['n_end'],
        $_POST['p_max'],
        $_POST['pt_max'],
        $_POST['ml_time'],
        $weeklyString,
    ]);

    $parentSid = $pdo->lastInsertId(); //取得剛加入父表的品項編號 //echo $lastsid;



    $data1 = $_POST['rest_svc'];


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

    // 加入圖片名稱
    $sqlChild3 = "INSERT INTO  `rest_img` (`rest_sid`,`img_name` ) VALUES (?,?)";
    $stmt3 = $pdo->prepare($sqlChild3);
    $stmt3->execute([
        $parentSid,
        $_POST['pro_img'],
    ]);

    $output['success'] = true;
}


header('Content-Type: application/json');
echo json_encode($output, JSON_UNESCAPED_UNICODE);
