<?php
require './partsNOEDIT/connect-db.php';


$output = [
    'success' => false,
    'postData' => $_POST,       #除錯用
    'code' => 0,
    'error' => []
];

if (!empty($_POST['name'])) {
    $isPass = true;
    $proCatID = $_POST['for_pet_type'] . $_POST['category_sid'] . $_POST['category_detail_sid'];

    // 循环生成新的 $newProSid，直到找到一个不與现有记录冲突的值
    $newProSidNum = 1;
    $newProSid = sprintf("%s%04d", $proCatID, $newProSidNum);
    while (true) {
        // 檢查 $newProSid 是否已存在資料庫中
        $sql_checkProSid = "SELECT COUNT(*) FROM `shop_product` WHERE `product_sid` = ?";
        $stmt_checkProSid = $pdo->prepare($sql_checkProSid);
        $stmt_checkProSid->execute([$newProSid]);
        $rowCount = $stmt_checkProSid->fetchColumn();

        if ($rowCount == 0) {
            // 如果 $newProSid 不存在資料庫中，退出循環
            break;
        }

        // 如果 $newProSid 已存在資料庫中，則遞增 $newProSidNum 加1=> $newProSid
        $newProSidNum++;
        $newProSid = sprintf("%s%04d", $proCatID, $newProSidNum);
    }

    #2.鍵入資料準備
    $sql_pro = "INSERT INTO `shop_product`(
        `product_sid`, `category_sid`, `category_detail_sid`,
         `supplier_sid`, `for_pet_type`, `name`, 
         `description`, `img`, `shelf_date`,
          `update_date`, `shelf_status`
          ) VALUES (
            ?,      ?,      ?,
            ?,      ?,      ?,
            ?,      ?,      NOW(),
            NOW(),  1
            )";

    $stmt_pro = $pdo->prepare($sql_pro);

    #3添加至子類別表格準備
    $sql_proDet = "INSERT INTO `shop_product_detail`(
        `product_detail_sid`, `product_sid`, `name`, `price`, `qty`, `img`, `for_age`) VALUES (
            ?,?,?,
            ?,?,?,
            ?)";

    $stmt_proDet = $pdo->prepare($sql_proDet);

    #3 添加至產品+規格關係表準備
    $sql_proSpec = "INSERT INTO `shop_product_specific`(
    `product_sid`, `product_detail_sid`, 
    `specific_sid`, `specific_detail_sid`) 
    VALUES (
        ?,?,
        ?,?
        )";
    $stmt_proSpec = $pdo->prepare($sql_proSpec);

    $name = isset($_POST['name']) ? htmlentities($_POST['name']) : "";
    $description = isset($_POST['description']) ? htmlentities($_POST['description']) : "";

    if ($isPass) {
        #2.鍵入資料
        $stmt_pro->execute([
            $newProSid,
            $_POST['category_sid'],
            $_POST['category_detail_sid'],
            $_POST['supplier_sid'],
            $_POST['for_pet_type'],
            $name,
            $description,
            $_POST['img']
        ]);


        //取得品項名稱
        //1. 先將資料庫的規格表資訊取出來
        $sql_spec = "SELECT * FROM `shop_specific`";
        $stmt_spec = $pdo->query($sql_spec)->fetchAll();
        //2. 依據輸入的大類與小類別規格，將其變成品項名稱
        //2-1 
        $specific_sid1 = empty($_POST['specific_sid1']) ? [] : $_POST['specific_sid1'];
        $specific_detail_sid1 = empty($_POST['specific_detail_sid1']) ? [] : $_POST['specific_detail_sid1'];
        $specific_sid2 = empty($_POST['specific_sid2']) ? [] : $_POST['specific_sid2'];
        $specific_detail_sid2 = empty($_POST['specific_detail_sid2']) ? [] : $_POST['specific_sid2'];

        $name = [];
        for ($i = 0, $max = count($specific_sid1); $i < $max; $i++) {
            if (!empty($specific_detail_sid2)) {
                $proDet1 = '';
                $proDet2 = '';
                for ($k = 0, $cmax = count($stmt_spec); $k < $cmax; $k++) {
                    if ($specific_sid1[$i] == $stmt_spec[$k]['specific_sid'] && $specific_detail_sid1[$i] == $stmt_spec[$k]['specific_detail_sid']) {
                        $proDet1 = $stmt_spec[$k]['detail_name'];
                        break;
                    }
                }
                for ($j = 0, $jmax = count($stmt_spec); $j < $jmax; $j++) {
                    if ($specific_sid2[$i] == $stmt_spec[$j]['specific_sid'] && $specific_detail_sid2[$i] == $stmt_spec[$j]['specific_detail_sid']) {
                        $proDet2 = $stmt_spec[$j]['detail_name'];
                        break;
                    }
                }
                $name[] = $proDet1 . "+" . $proDet2;
            } else {
                for ($k = 0, $cmax = count($stmt_spec); $k < $cmax; $k++) {
                    if ($specific_sid1[$i] == $stmt_spec[$k]['specific_sid'] && $specific_detail_sid1[$i] == $stmt_spec[$k]['specific_detail_sid']) {
                        $name[] = $stmt_spec[$k]['detail_name'];
                    }
                };
            }
        }


        #3添加至子類別表格
        $proNewArr = [];
        $proNewArr['product_detail_sid'] = $_POST['product_detail_sid'];

        foreach ($proNewArr['product_detail_sid'] as $k => $v) {
            $stmt_proDet->execute([
                sprintf('%02d', $v),
                $newProSid,
                $name[$k],
                $_POST['price'][$k],
                $_POST['qty'][$k],
                $_POST['img1'][$k],
                $_POST['for_age'][$k]
            ]);
        };

        #添加至產品+規格關係表
        if (!empty($_POST['specific_detail_sid2'])) {
            foreach ($proNewArr['product_detail_sid'] as $k => $v) {
                $stmt_proSpec->execute([
                    $newProSid,
                    sprintf('%02d', $v),
                    $_POST['specific_sid1'][$k],
                    $_POST['specific_detail_sid1'][$k]
                ]);
            }
            foreach ($proNewArr['product_detail_sid'] as $k => $v) {
                $stmt_proSpec->execute([
                    $newProSid,
                    sprintf('%02d', $v),
                    $_POST['specific_sid2'][$k],
                    $_POST['specific_detail_sid2'][$k]
                ]);
            }
        } else {
            foreach ($proNewArr['product_detail_sid'] as $k => $v) {
                $stmt_proSpec->execute([
                    $newProSid,
                    sprintf('%02d', $v),
                    $_POST['specific_sid1'][$k],
                    $_POST['specific_detail_sid1'][$k]
                ]);
            }
        }

        $output['success'] = !!$stmt_pro->rowCount();
    }
}

header('Content-Type: application/json');
echo json_encode($output, JSON_UNESCAPED_UNICODE);
