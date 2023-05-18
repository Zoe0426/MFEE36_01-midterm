<?php
include './partsNOEDIT/connect-db.php';

#我要回傳給HTML的訊息
$output = [
    'success' => false,
    'postData' => $_POST, # 除錯用的
    'code' => 0,
    'error' => [],
];

$board_name = isset($_POST['board_name']) ? $_POST['board_name'] : " ";

$sql = "INSERT INTO `post_board`
(`board_name`) VALUES (?)";
#問號的部份，是我們先不給值，保留起來，當要加進資料庫的時候（execute）我才把問號的內容補進去。而日期，在寫SQL語法的時候，就己經是用電腦自動生的now()，所以在那裡就有值了，下面就不需要了

// echo $sql;

$stmt = $pdo->prepare($sql);

$stmt->execute([
    $board_name
]);

if (!!$stmt->rowCount()) { //如果表格新增成功，會是true，如果沒成功會是false
    $output['success'] = true;
    $output['message'] = "新增成功";
}

header('Content-Type: application/json');
echo json_encode($output, JSON_UNESCAPED_UNICODE);
