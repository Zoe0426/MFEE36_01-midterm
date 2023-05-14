<?php
header('Content-Type: application/json');

$output = [
    'filename' => '',
    'files' => $_FILES,       //除錯用
];
// print_r($_FILES);


if (!empty($_FILES['shopTepProImg'])) {
    $filename = sha1($_FILES['shopTepProImg']['name'] . uniqid()) . 'jpg';
    if (move_uploaded_file($_FILES['shopTepProImg']['tmp_name'], "./s_Imgs/$filename")) {
        $output['filename'] = $filename;
    } else {
        $output['error'] = '移動檔案時發生錯誤';
    }
};
header('Content-Type: application/json');
echo json_encode($output, JSON_UNESCAPED_UNICODE);
