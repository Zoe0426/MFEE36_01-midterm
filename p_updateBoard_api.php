<?php
require './partsNOEDIT/connect-db.php';

$output = [
    'success' => false, #更新成功或失敗的結果（MUST）
    'postData' => $_POST, #除錯用的
    'code' => 0,
    'error' => [],
];

if (!empty($_POST['board_name'])) { #若符合某些條件，則可以往下走

    $isPass = true;
    #TODO:檢查欄位資料，判斷格式錯，isPass設為false (MUST)
    if (empty($_POST['board_name'])) {
        $isPass = false;
        $output['error']['board_name'] = '必填！請輸入文字';
    }
    #TODO:整理變數，轉換資料格式

    $sql = "UPDATE `post_board` SET
    `board_name`=? WHERE `board_sid`=?";

    $stmt = $pdo->prepare($sql);

    if ($isPass) {
        $stmt->execute([
            $_POST['board_name'],
            $_POST['board_sid']
        ]);

        $output['success'] = $stmt->rowCount();
    }
}
header('Content-Type: application/json');
echo json_encode($output, JSON_UNESCAPED_UNICODE);
