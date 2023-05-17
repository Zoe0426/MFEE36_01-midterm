<?php
include './partsNOEDIT/connect-db.php';

#我要回傳給HTML的訊息
$output = [
    'success' => false,
    'postData' => $_POST, # 除錯用的
    'code' => 0,
    'error' => [],
];

//父表格變數
$admin_name = isset($_POST['admin_name']) ? $_POST['admin_name'] : " ";
$board_sid = isset($_POST['board_name']) ? intval($_POST['board_name']) : 0;
$post_title = isset($_POST['post_title']) ? $_POST["post_title"] : " ";
$post_content = isset($_POST['post_content']) ? $_POST["post_content"] : " ";


//子表格變數
$file = isset($_POST['file']) ? $_POST['file'] : "";


$sql = "INSERT INTO `post_list_admin`
(`admin_name`, `board_sid`, `post_title`, 
`post_content`, `post_date`, `update_date`) 
VALUES (?,?,?,?,now(),now())";
#問號的部份，是我們先不給值，保留起來，當要加進資料庫的時候（execute）我才把問號的內容補進去。而日期，在寫SQL語法的時候，就己經是用電腦自動生的now()，所以在那裡就有值了，下面就不需要了

// echo $sql;

$stmt = $pdo->prepare($sql);

$stmt->execute([
    $admin_name,
    $board_sid,
    $post_title,
    $post_content,
]);

if (!!$stmt->rowCount()) { //如果表格新增成功，會是true，如果沒成功會是false
    $output['success'] = true;
    $output['message'] = "父表格新增成功";
}





$fatherLastSid = $pdo->lastInsertId(); //先用一個變數是抓取父表格輸入的最新一筆資料



#file檔案讀取，原本應該要用這個，但再研究看看
if (!empty($_FILES['file'])) {
    $filename = sha1($_FILES['file']['name'] . uniqid()) . '.jpg'; //先把file名字編碼過
    move_uploaded_file($_FILES['file']['tmp_name'], "./postImg/{$filename}"); //把圖片存到img檔案裡

    $sql2 = "INSERT INTO `post_file`
    (`post_sid`, `file_type`, `file`) VALUES (?,?,?)";


    $stmt = $pdo->prepare($sql2);

    $stmt->execute([
        $fatherLastSid,
        "F01",
        $filename //因為file裡面東西太多了，所以要給他$filename去顯示（資料庫也只能讀取檔名或二進位的字串）
    ]);

    if (!!$stmt->rowCount()) {
        //如果表格新增成功，會是true，如果沒成功會是false 
        //(rowCount()原本是一個數值，是資料庫異動的筆數（如1），加一個!讓數值轉型成boolean值，但他會變false（not 1），所以再給他一個!讓他變回true)
        $output['success'] = true;
        $output['message2'] = "子表格新增成功";
    }
};




header('Content-Type: application/json');
echo json_encode($output, JSON_UNESCAPED_UNICODE);
