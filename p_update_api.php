<?php
require './partsNOEDIT/connect-db.php';

$output = [
    'success' => false, #更新成功或失敗的結果（MUST）
    'postData' => $_POST, #除錯用的
    'code' => 0,
    'error' => [],
];

if (!empty($_POST['post_title']) and !empty($_POST['post_sid'])) { #若符合某些條件，則可以往下走

    $isPass = true;
    #TODO:檢查欄位資料，判斷格式錯，isPass設為false (MUST)
    if (empty($_POST['post_content'])) {
        $isPass = false;
        $output['error']['post_content'] = '必填！請輸入文字';
    }
    #TODO:整理變數，轉換資料格式

    $sql = "UPDATE `post_list_admin` SET
    `admin_name`=?,
    `board_sid`=?,
    `post_title`=?,
    `post_content`=?,
    `update_date`=NOW()
    WHERE `post_sid`=?";

    $stmt = $pdo->prepare($sql);

    if ($isPass) {
        $stmt->execute([
            $_POST['admin_name'],
            $_POST['board_sid'],
            $_POST['post_title'],
            $_POST['post_content'],
            $_POST['post_sid'],
        ]);

        $output['success'] = $stmt->rowCount();
    }
}

header('Content-Type: application/json');
echo json_encode($output, JSON_UNESCAPED_UNICODE);
