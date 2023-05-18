<?php

require './partsNOEDIT/connect-db.php';

$output = [
    'success' => false,
    'postData' => $_POST, # 除錯用的
    'code' => 0,
];



if (!empty($_POST['account']) and !empty($_POST['password'])) {
    $sql = "SELECT * FROM mem_admin WHERE admin_account=?";
    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        $_POST["account"]
    ]);

    $row = $stmt->fetch();

    if (empty($row)) {
        $output['code'] = 410; #帳號錯誤
    } else {
        if (password_verify($_POST['password'], $row['admin_password'])) {
            #密碼正確
            $_SESSION['admin'] = $row;
            $output['success'] = true;
        } else {
            #密碼錯誤
            $output['code'] = 420;
        }
    }
}

header('Content-Type: application/json');
echo json_encode($output, JSON_UNESCAPED_UNICODE);
