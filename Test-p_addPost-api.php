<?php
#我要回傳給HTML的訊息
$output = [
    'success' => false,
    'postData' => $_POST, # 除錯用的
    'code' => 0,
    'error' => [],
    'message' => ''
];
#file檔案讀取，原本應該要用這個，但再研究看看
if (!empty($_FILES['file'])) {
    $filename = sha1($_FILES['file']['name'] . uniqid()) . '.jpg'; //先把file名字編碼過
    move_uploaded_file($_FILES['file']['name'], "./postImg/{$filename}"); //把圖片存到img檔案裡
    $output['success'] = true;
    $output['message'] = $_FILES['file']['type'];
};




header('Content-Type: application/json');
echo json_encode($output, JSON_UNESCAPED_UNICODE);
