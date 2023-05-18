<?php
require './partsNOEDIT/connect-db.php';

$output = [
    'success' => false,
    'postData' => $_POST, # 除錯用的
    'code' => 0,
    'error' => '',

];
$sid = isset($_POST["member_sid"]) ? $_POST["member_sid"] : '';
$name = isset($_POST['member_name']) ? $_POST['member_name'] : '';
$email =  isset($_POST['member_email']) ? $_POST['member_email'] : '';
$password = isset($_POST['member_password']) ? $_POST['member_password'] : '';
$mobile = isset($_POST['member_mobile']) ? $_POST['member_mobile'] : '';
$gender = isset($_POST['member_gender']) ? $_POST['member_gender'] : '';
$member_birth = isset($_POST['member_birth']) ? date('Y-m-d', strtotime($_POST['member_birth'])) : '';
$member_pet = isset($_POST['member_pet']) ? $_POST['member_pet'] : '';
$member_level = isset($_POST['member_level']) ? $_POST['member_level'] : '';
$member_ID = isset($_POST['member_ID']) ? $_POST['member_ID'] : '';


if (!empty($name) and !empty($email) and !empty($password) and !empty($mobile) and !empty($gender) and !empty($member_birth) and !empty($member_pet) and !empty($member_level) and !empty($member_ID)) {

    $sql = "UPDATE mem_member SET 
    member_name=?, 
    member_email=?, 
    member_password=?, 
    member_mobile=?, 
    member_gender=?,
    member_birth=?,
    member_pet=?,
    member_level=?,
    member_ID=?,
    update_time=NOW()
    WHERE member_sid=?";


    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $name,
        $email,
        $password,
        $mobile,
        $gender,
        $member_birth,
        $member_pet,
        $member_level,
        $member_ID,
        $sid,
    ]);
}


$output['success'] = !!$stmt->rowCount();

header('Content-Type: application/json');
echo json_encode($output, JSON_UNESCAPED_UNICODE);
