<?php
include './partsNOEDIT/connect-db.php' ?>;
<?php
$output = [
   
];
$sql_post="SELECT `admin_name` FROM `post_list_admin`";
$stmt=$pdo->query($sql_post);
$r_post=$stmt->fetchAll();

$output['post_list']=$r_post;
print_r($output);

// header('Content-Type: application/json');
// echo json_encode($output, JSON_UNESCAPED_UNICODE);