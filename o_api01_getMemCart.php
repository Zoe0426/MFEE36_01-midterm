<?php
// sbname: asdf
// sbmobile: 
// sbmemsid: 
require './partsNOEDIT/connect-db.php';

$output = [
    'success' => false,
];
$searchBy = isset($_POST['searchBy']) ? intval($_POST['searchBy']) : 0;
if (!empty($_POST['searchBy'])) {
    $sbmemsid = isset($_POST['sbmemsid']) ? $_POST['sbmemsid'] : null;
    $sbmobile = isset($_POST['sbmobile']) ? $_POST['sbmobile'] : null;
    $sbname = isset($_POST['sbname']) ? $_POST['sbname'] : null;

    if ($searchBy == 3) {
        $sqlmem = "SELECT `member_sid`, `member_name`, `member_mobile`, `member_birth` FROM `mem_member` WHERE `member_sid` = ? ";
        $stm = $pdo->prepare($sqlmem);
        $stm->execute([$sbmemsid]);
        $data = $stm->fetch();

        $output['success'] = true;
        $output['sid'] = $data['member_sid'];
        $output['name'] = $data['member_name'];
        $output['mobile'] = $data['member_mobile'];
        $output['birth'] = $data['member_birth'];
    }

    if ($searchBy == 2) {
        $sqlmobile = "SELECT `member_sid`, `member_name`, `member_mobile`, `member_birth` FROM `mem_member` WHERE `member_mobile` = ? ";
        $stm = $pdo->prepare($sqlmobile);
        $stm->execute([$sbmobile]);
        $data = $stm->fetch();

        $output['success'] = true;
        $output['sid'] = $data['member_sid'];
        $output['name'] = $data['member_name'];
        $output['mobile'] = $data['member_mobile'];
        $output['birth'] = $data['member_birth'];
    }

    if ($searchBy == 1) {
        $sqlname = "SELECT `member_sid`, `member_name`, `member_mobile`, `member_birth` FROM `mem_member` WHERE `member_name` = ? ";
        $stm = $pdo->prepare($sqlname);
        $stm->execute([$sbname]);
        $data = $stm->fetch();

        $output['success'] = true;
        $output['sid'] = $data['member_sid'];
        $output['name'] = $data['member_name'];
        $output['mobile'] = $data['member_mobile'];
        $output['birth'] = $data['member_birth'];
    }
}

// print_r($data);


header('Content-Type: application/json');
echo json_encode($output, JSON_UNESCAPED_UNICODE);
