<?php
require './partsNOEDIT/connect-db.php';


$output = [
    'success' => false,
    'postData' => $_POST,       #除錯用
    'code' => 0,
    'error' => []
];

if (!empty($_POST['pro_name'])) {
    $isPass = true;


    #1.主商品更新資料準備
    $sql_proUpdate = "UPDATE `shop_pro` SET 
    `pro_sid`=?,
    `cat_sid`=?,
    `catDet_sid`=?,
    `sup_sid`=?,
    `pro_for`=?,
    `pro_name`=?,
    `pro_describe`=?,
    `pro_img`=?,
    `pro_onWeb`=?,
    `pro_update`=NOW(),
    `pro_status`=?
    WHERE `pro_sid`=?";


    $stmt_proUpdate = $pdo->prepare($sql_proUpdate);

    $pro_name = isset($_POST['pro_name']) ? htmlentities($_POST['pro_name']) : "";
    $pro_describe = isset($_POST['pro_describe']) ? htmlentities($_POST['pro_describe']) : "";

    #2.刪除細項商品的表格準備

    $sql_proDetDel = sprintf("DELETE FROM `shop_prodet` WHERE `pro_sid`='%s'", $_POST['pro_sid']);

    #3添加細項商品表格準備
    $sql_proDet = "INSERT INTO `shop_prodet`(
        `proDet_sid`, `pro_sid`, `proDet_name`, `proDet_price`, `proDet_qty`, `proDet_img`, `pro_forAge`) VALUES (
            ?,?,?,
            ?,?,?,
            ?)";

    $stmt_proDet = $pdo->prepare($sql_proDet);

    #4.刪除商品規格的表格準備
    $sql_proSpecDel = sprintf("DELETE FROM `shop_prospec` WHERE `prod_sid`='%s'", $_POST['pro_sid']);


    #5.添加至產品+規格關係表準備
    $sql_proSpec = "INSERT INTO `shop_prospec`(
    `prod_sid`, `prodDet_sid`, 
    `spec_sid`, `specDet_sid`) 
    VALUES (
        ?,?,
        ?,?
        )";
    $stmt_proSpec = $pdo->prepare($sql_proSpec);


    if ($isPass) {
        #1.主商品更新
        $stmt_proUpdate->execute([
            $_POST['pro_sid'],
            $_POST['cat_sid'],
            $_POST['catDet_sid'],
            $_POST['sup_sid'],
            $_POST['pro_for'],
            $pro_name,
            $pro_describe,
            $_POST['pro_img'],
            $_POST['pro_onWeb'],
            $_POST['pro_status'],
            $_POST['pro_sid']
        ]);

        #2.刪除細項商品
        $pdo->query($sql_proDetDel);

        //取得品項名稱
        //1. 先將資料庫的規格表資訊取出來
        $sql_spec = "SELECT * FROM `shop_spec`";
        $stmt_spec = $pdo->query($sql_spec)->fetchAll();
        //2. 依據輸入的大類與小類別規格，將其變成品項名稱
        //2-1 
        $spec_sid1 = empty($_POST['spec_sid1']) ? [] : $_POST['spec_sid1'];
        $specDet_sid1 = empty($_POST['specDet_sid1']) ? [] : $_POST['specDet_sid1'];
        $spec_sid2 = empty($_POST['spec_sid2']) ? [] : $_POST['spec_sid2'];
        $specDet_sid2 = empty($_POST['specDet_sid2']) ? [] : $_POST['spec_sid2'];

        $proDet_name = [];
        for ($i = 0, $max = count($spec_sid1); $i < $max; $i++) {
            if (!empty($specDet_sid2)) {
                $proDet1 = '';
                $proDet2 = '';
                for ($k = 0, $cmax = count($stmt_spec); $k < $cmax; $k++) {
                    if ($spec_sid1[$i] == $stmt_spec[$k]['spec_sid'] && $specDet_sid1[$i] == $stmt_spec[$k]['specDet_sid']) {
                        $proDet1 = $stmt_spec[$k]['specDet_name'];
                        break;
                    }
                }
                for ($j = 0, $jmax = count($stmt_spec); $j < $jmax; $j++) {
                    if ($spec_sid2[$i] == $stmt_spec[$j]['spec_sid'] && $specDet_sid2[$i] == $stmt_spec[$j]['specDet_sid']) {
                        $proDet2 = $stmt_spec[$j]['specDet_name'];
                        break;
                    }
                }
                $proDet_name[] = $proDet1 . "+" . $proDet2;
            } else {
                for ($k = 0, $cmax = count($stmt_spec); $k < $cmax; $k++) {
                    if ($spec_sid1[$i] == $stmt_spec[$k]['spec_sid'] && $specDet_sid1[$i] == $stmt_spec[$k]['specDet_sid']) {
                        $proDet_name[] = $stmt_spec[$k]['specDet_name'];
                    }
                };
            }
        }

        #3添加至子類別表格
        $proNewArr = [];
        $proNewArr['proDet_sid'] = $_POST['proDet_sid'];

        foreach ($proNewArr['proDet_sid'] as $k => $v) {
            $stmt_proDet->execute([
                sprintf('%02d', $v),
                $_POST['pro_sid'],
                $proDet_name[$k],
                $_POST['proDet_price'][$k],
                $_POST['proDet_qty'][$k],
                $_POST['pro_img1'][$k],
                $_POST['pro_forAge'][$k]
            ]);
        };


        #4.刪除細項商品
        $pdo->query($sql_proSpecDel);

        #5.添加至產品+規格關係表
        foreach ($proNewArr['proDet_sid'] as $k => $v) {
            $stmt_proSpec->execute([
                $_POST['pro_sid'],
                sprintf('%02d', $v),
                $_POST['spec_sid1'][$k],
                $_POST['specDet_sid1'][$k]
            ]);
        }
        if (!empty($_POST['specDet_sid2'])) {
            foreach ($proNewArr['proDet_sid'] as $k => $v) {
                $stmt_proSpec->execute([
                    $_POST['pro_sid'],
                    sprintf('%02d', $v),
                    $_POST['spec_sid2'][$k],
                    $_POST['specDet_sid2'][$k]
                ]);
            }
        }
        $output['success'] = !!$stmt_proSpec->rowCount();
    }
}

header('Content-Type: application/json');
echo json_encode($output, JSON_UNESCAPED_UNICODE);
