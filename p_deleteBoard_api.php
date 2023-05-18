<?php
require './partsNOEDIT/connect-db.php';

$output = [
    'success' => false, #刪除成功或失敗的結果（MUST）
    'code' => 0,
    'error' => [],
];

if (!empty($_GET['board_sid'])) {
    $board_sid = intval($_GET['board_sid']);
    $sql = "DELETE FROM `post_board` WHERE `board_sid`={$board_sid}";
    $pdo->query($sql);
    $output["board_sid"] = $_GET['board_sid'];
    $output['success'] = true;
} else {
    $output['dataStatus'] = "no board sid";
}


// $comeFrom = 'p_readPost_api.php';
// if(! empty($_SERVER['HTTP_REFERER'])){
//     $comeFrom = $_SERVER['HTTP_REFERER'];
// }



header('Content-Type: application/json');
echo json_encode($_GET['post_sid'], JSON_UNESCAPED_UNICODE);
