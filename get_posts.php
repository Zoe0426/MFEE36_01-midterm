<?php
// 在這裡連接資料庫
require './partsNOEDIT/connect-db.php';


$board_sid = isset($_GET['board_sid']) ? intval($_GET['board_sid']) : 0;

$output["board_sid"] = $board_sid;
// 根據 $boardSid 從資料庫中檢索相應的文章
$sql = "SELECT * FROM post_list_admin WHERE board_sid = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$board_sid]);

$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);




$output = [
    'success' => true,
    'postData' => $posts,

];



header('Content-Type: application/json');
echo json_encode($output, JSON_UNESCAPED_UNICODE);
