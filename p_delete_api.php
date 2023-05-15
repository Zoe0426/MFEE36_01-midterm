<?php
require './partsNOEDIT/connect-db.php';

$output=[
    'success' => false, #刪除成功或失敗的結果（MUST）
    'code' => 0,
    'error' => [],
];

if(!empty($_GET['post_sid'])){
    $post_sid=intval($_GET['post_sid']);
    $sql="DELETE FROM `post_list_admin` WHERE `post_sid`={$post_sid}";
    $pdo->query($sql);
    $output["post_sid"]=$_GET['post_sid'];
    $output['success']= true;

}else{
    $output['dataStatus']="no post sid";
}




// $comeFrom = 'p_readPost_api.php';
// if(! empty($_SERVER['HTTP_REFERER'])){
//     $comeFrom = $_SERVER['HTTP_REFERER'];
// }



header('Content-Type: application/json');
echo json_encode($_GET['post_sid'], JSON_UNESCAPED_UNICODE);
?>