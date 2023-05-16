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
    $proCatID = $_POST['pro_for'] . $_POST['cat_sid'] . $_POST['catDet_sid'];
    $sql_proSid = sprintf("SELECT IFNULL(MAX(pro_sid), '%s0000') FROM `shop_pro`", $proCatID);
    $lastProCatID = $pdo->query($sql_proSid)->fetchColumn();

    #2.鍵入資料準備
    $sql_pro = "INSERT INTO `shop_pro`(
        `pro_sid`, `cat_sid`, `catDet_sid`,
         `sup_sid`, `pro_for`, `pro_name`, 
         `pro_describe`, `pro_img`, `pro_onWeb`,
          `pro_update`, `pro_status`
          ) VALUES (
            ?,      ?,      ?,
            ?,      ?,      ?,
            ?,      ?,      NOW(),
            NOW(),  1
            )";

    $stmt_pro = $pdo->prepare($sql_pro);


    if (empty($lastProCatID)) {
        //沒有這類的產品，則pro_sid:
        $newProSid = sprintf("%s0001", $proCatID);
    } else {
        //有這類的產品，則pro_sid:
        $newProSidNum = (int)substr($lastProCatID, 4) + 1;
        $newProSid = sprintf("%s%04d", $proCatID, $newProSidNum);
    }

    #3添加至子類別表格準備
    $sql_proDet = "INSERT INTO `shop_prodet`(
        `proDet_sid`, `pro_sid`, `proDet_name`, `proDet_price`, `proDet_qty`, `proDet_img`, `pro_forAge`) VALUES (
            ?,?,?,
            ?,?,?,
            ?)";

    $stmt_proDet = $pdo->prepare($sql_proDet);

    #3 添加至產品+規格關係表準備
    $sql_proSpec = "INSERT INTO `shop_prospec`(
    `prod_sid`, `prodDet_sid`, 
    `spec_sid`, `specDet_sid`) 
    VALUES (
        ?,?,
        ?,?
        )";
    $stmt_proSpec = $pdo->prepare($sql_proSpec);

    $pro_name = isset($_POST['pro_name']) ? htmlentities($_POST['pro_name']) : "";
    $pro_describe = isset($_POST['pro_describe']) ? htmlentities($_POST['pro_describe']) : "";

    if ($isPass) {
        #2.鍵入資料
        $stmt_pro->execute([
            $newProSid,
            $_POST['cat_sid'],
            $_POST['catDet_sid'],
            $_POST['sup_sid'],
            $_POST['pro_for'],
            $pro_name,
            $pro_describe,
            $_POST['pro_img']
        ]);


        //取得品項名稱
        //1. 先將資料庫的規格表資訊取出來
        $sql_spec = "SELECT * FROM `shop_spec`";
        $stmt_spec = $pdo->query($sql_spec)->fetchAll();
        //2. 依據輸入的大類與小類別規格，將其變成品項名稱
        //2-1 
        $spec_sid1 = $_POST['spec_sid1'];
        $specDet_sid1 = $_POST['specDet_sid1'];
        $spec_sid2 = $_POST['spec_sid2'];
        $specDet_sid2 = $_POST['specDet_sid2'];

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
                $newProSid,
                $proDet_name[$k],
                $_POST['proDet_price'][$k],
                $_POST['proDet_qty'][$k],
                $_POST['pro_img1'][$k],
                $_POST['pro_forAge'][$k]
            ]);
        };

        #添加至產品+規格關係表
        foreach ($proNewArr['proDet_sid'] as $k => $v) {
            $stmt_proSpec->execute([
                $newProSid,
                sprintf('%02d', $v),
                $_POST['spec_sid1'][$k],
                $_POST['specDet_sid1'][$k]
            ]);
        }
        foreach ($proNewArr['proDet_sid'] as $k => $v) {
            $stmt_proSpec->execute([
                $newProSid,
                sprintf('%02d', $v),
                $_POST['spec_sid2'][$k],
                $_POST['specDet_sid2'][$k]
            ]);
        }
        $output['success'] = !!$stmt_pro->rowCount();
    }
}

header('Content-Type: application/json');
echo json_encode($output, JSON_UNESCAPED_UNICODE);
