<?php
require './partsNOEDIT/connect-db.php';


$post_sid = intval(67);
    $sql="DELETE FROM `post_list_admin` WHERE `post_sid`={$post_sid}";
    $pdo->query($sql);

// $comeFrom = 'p_readPost_api.php';
// if(! empty($_SERVER['HTTP_REFERER'])){
//     $comeFrom = $_SERVER['HTTP_REFERER'];
// }



header('Content-Type: application/json');
echo json_encode($post_sid, JSON_UNESCAPED_UNICODE);
?>