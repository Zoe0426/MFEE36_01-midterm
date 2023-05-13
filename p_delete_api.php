<?php
require './partsNOEDIT/connect-db.php';

$output=[
    'success' => false, #刪除成功或失敗的結果（MUST）
    'code' => 0,
    'error' => [],
];

if(!empty($_GET['post_sid'])){
    $post_sid=$_GET['post_sid'] ? intval($_GET['post_sid']) : '';
}


$post_sid=isset($_GET['sid'])?intval($_GET['post_sid']):0;
$sql="DELETE FROM `post_list_admin` WHERE `post_sid`={$post_sid}";

$pdo->query($sql);



header('Content-Type: application/json');
echo json_encode($output, JSON_UNESCAPED_UNICODE);
?>