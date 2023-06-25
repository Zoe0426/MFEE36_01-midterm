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


    #1.主商品更新資料準備
    $sql_proUpdate = "UPDATE `shop_product` SET 
    `product_sid`=?,
    `category_sid`=?,
    `category_detail_sid`=?,
    `supplier_sid`=?,
    `for_pet_type`=?,
    `name`=?,
    `description`=?,
    `img`=?,
    `shelf_date`=?,
    `update_date`=NOW(),
    `shelf_status`=?
    WHERE `product_sid`=?";


    $stmt_proUpdate = $pdo->prepare($sql_proUpdate);

    $name = isset($_POST['name']) ? htmlentities($_POST['name']) : "";
    $description = isset($_POST['description']) ? htmlentities($_POST['description']) : "";

    #2.刪除細項商品的表格準備

    $sql_proDetDel = sprintf("DELETE FROM `shop_product_detail` WHERE `product_sid`='%s'", $_POST['product_sid']);

    #3添加細項商品表格準備
    $sql_proDet = "INSERT INTO `shop_product_detail`(
        `product_detail_sid`, `product_sid`, `name`, `price`, `qty`, `img`, `for_age`) VALUES (
            ?,?,?,
            ?,?,?,
            ?)";

    $stmt_proDet = $pdo->prepare($sql_proDet);

    #4.刪除商品規格的表格準備
    $sql_proSpecDel = sprintf("DELETE FROM `shop_product_specific` WHERE `product_sid`='%s'", $_POST['product_sid']);


    #5.添加至產品+規格關係表準備
    $sql_proSpec = "INSERT INTO `shop_product_specific`(
    `product_sid`, `product_detail_sid`, 
    `specific_sid`, `specific_detail_sid`) 
    VALUES (
        ?,?,
        ?,?
        )";
    $stmt_proSpec = $pdo->prepare($sql_proSpec);


    if ($isPass) {
        #1.主商品更新
        $stmt_proUpdate->execute([
            $_POST['product_sid'],
            $_POST['category_sid'],
            $_POST['category_detail_sid'],
            $_POST['supplier_sid'],
            $_POST['for_pet_type'],
            $name,
            $description,
            $_POST['img'],
            $_POST['shelf_date'],
            $_POST['shelf_status'],
            $_POST['product_sid']
        ]);

        #2.刪除細項商品
        $pdo->query($sql_proDetDel);

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
                $_POST['product_sid'],
                $name[$k],
                $_POST['price'][$k],
                $_POST['qty'][$k],
                $_POST['img1'][$k],
                $_POST['for_age'][$k]
            ]);
        };


        #4.刪除細項商品
        $pdo->query($sql_proSpecDel);

        #5.添加至產品+規格關係表
        foreach ($proNewArr['product_detail_sid'] as $k => $v) {
            $stmt_proSpec->execute([
                $_POST['product_sid'],
                sprintf('%02d', $v),
                $_POST['specific_sid1'][$k],
                $_POST['specific_detail_sid1'][$k]
            ]);
        }
        if (!empty($_POST['specific_detail_sid2'])) {
            foreach ($proNewArr['product_detail_sid'] as $k => $v) {
                $stmt_proSpec->execute([
                    $_POST['product_sid'],
                    sprintf('%02d', $v),
                    $_POST['specific_sid2'][$k],
                    $_POST['specific_detail_sid2'][$k]
                ]);
            }
        }
        $output['success'] = !!$stmt_proSpec->rowCount();
    }
}

header('Content-Type: application/json');
echo json_encode($output, JSON_UNESCAPED_UNICODE);
