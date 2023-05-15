<?php
require './partsNOEDIT/connect-db.php';
$output = [
    'success' => false, #更新成功或失敗的結果（MUST）
    'postData' => $_POST, # 除錯用的
    'code' => 0,
    'error' => [],
];



if (!empty($_POST['rest_name']) and !empty($_POST['rest_sid'])) {


    // 用餐時間更新
    $selectedValue = $_POST['ml_time'];
    $sid = $_POST['rest_sid'];

    // 執行 SQL 語句來更新特定資料列
    $sql = "UPDATE rest_info SET ml_time = :selectedValue WHERE rest_sid = :sid";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':selectedValue', $selectedValue, PDO::PARAM_STR);
    $stmt->bindParam(':sid', $sid, PDO::PARAM_INT);
    $stmt->execute();


    // 星期更新
    $wselectedValues = $_POST['weekly'];

    // 將所有的 checkbox 值合併為一個字串，以逗號分隔
    $selectedString = implode(',', $wselectedValues);

    // 執行 SQL 語句來刪除舊值並更新資料表
    $sql = "UPDATE `rest_info` SET weekly = :selectedString WHERE rest_sid = :sid";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':selectedString', $selectedString, PDO::PARAM_STR);
    $stmt->bindParam(':sid', $sid, PDO::PARAM_INT);
    $stmt->execute();


    // 空值設定
    $dateEnd =  $_POST['date_end'];
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

    // rest_info 資料更新
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

        $dateEnd,
        $_POST['m_start'],
        $m_end,

        $e_start,
        $e_end,
        $n_start,

        $_POST['n_end'],
        $_POST['p_max'],
        $_POST['pt_max'],



        $_POST['rest_sid'],
    ]);


    if (!empty($_POST['rest_sid'])) {
        $delSid = $_POST['rest_sid'];

        // 刪除資料庫中對應的項目
        $rsqlDelete = "DELETE FROM rest_c_rr WHERE rest_sid = :restSid";
        $rstmDelete = $pdo->prepare($rsqlDelete);
        $rstmDelete->execute(['restSid' => $delSid]);

        // 插入被勾選的項目
        $restRule = $_POST['rest_rule'];
        $rsqlInsert = "INSERT INTO rest_c_rr (rest_sid, r_sid) VALUES (:restSid, :rSid)";
        $rstmInsert = $pdo->prepare($rsqlInsert);
        foreach ($restRule as $rSid) {
            $rstmInsert->execute(['restSid' => $delSid, 'rSid' => $rSid]);
        }

        $ssqlDelete = "DELETE FROM rest_c_rs WHERE rest_sid = :restSid";
        $sstmDelete = $pdo->prepare($ssqlDelete);
        $sstmDelete->execute(['restSid' => $delSid]);


        $restSvc = $_POST['rest_svc'];
        $ssqlInsert = "INSERT INTO rest_c_rs (rest_sid, s_sid) VALUES (:restSid, :sSid)";
        $sstmInsert = $pdo->prepare($ssqlInsert);
        foreach ($restSvc as $sSid) {
            $sstmInsert->execute(['restSid' => $delSid, 'sSid' => $sSid]);
        }

        $output['success'] = true;
    }
}
header('Content-Type: application/json');
echo json_encode($output, JSON_UNESCAPED_UNICODE);
