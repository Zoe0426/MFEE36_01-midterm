<?php
require './partsNOEDIT/connect-db.php';

// $output = [
//     'success' => false,
//     'postData' => $_POST, # 除錯用的
//     'code' => 0,
//     'error' => '',

// ];
$coupon_sid = isset($_GET["coupon_sid"]) ? $_GET["coupon_sid"] : '';
$sql_cupon_detail = "SELECT `coupon_sid`, `coupon_code`, `coupon_name`, `coupon_price`, `coupon_startDate`, `coupon_expDate` FROM `mem_coupon_type` WHERE coupon_sid=:sid";
$stmt_coupon_detail = $pdo->prepare($sql_cupon_detail);
$stmt_coupon_detail->bindValue(':sid', $coupon_sid, PDO::PARAM_STR);
$stmt_coupon_detail->execute();
$r1 = $stmt_coupon_detail->fetch(PDO::FETCH_ASSOC);



$member_sid = isset($_GET["member_sid"]) ? $_GET["member_sid"] : '';
$sql_member_detail = "SELECT `member_sid`, `member_name`, `member_email`, `member_mobile` FROM `mem_member` WHERE member_sid=:sid";
$stmt_member_detail = $pdo->prepare($sql_member_detail);
$stmt_member_detail->bindValue(':sid', $member_sid, PDO::PARAM_STR);
$stmt_member_detail->execute();
$r2 = $stmt_member_detail->fetch(PDO::FETCH_ASSOC);

// $output['r1'] = $r1;
// $output['r2'] = $r2;

// // $output['success'] = !!$stmt_coupon_detail->rowCount();
// header('Content-Type: application/json');
// echo json_encode($output, JSON_UNESCAPED_UNICODE);

?>


<?php include './partsNOEDIT/html-head.php' ?>
<?php include './partsNOEDIT/navbar.php' ?>
<div class="container">
    <div class="row">
        <h3>優惠券資訊</h3>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th scope="col">優惠券編號</th>
                    <th scope="col">優惠券代碼</th>
                    <th scope="col">優惠券名稱</th>
                    <th scope="col">金額</th>
                    <th scope="col">開始日</th>
                    <th scope="col">結束日</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?= $r1['coupon_sid'] ?></td>
                    <td><?= $r1['coupon_code'] ?></td>
                    <td><?= $r1['coupon_name'] ?></td>
                    <td><?= $r1['coupon_price'] ?></td>
                    <td><?= $r1['coupon_startDate'] ?></td>
                    <td><?= $r1['coupon_expDate'] ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="row mt-3">
        <h3>會員資訊</h3>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th scope="col">會員編號</th>
                    <th scope="col">會員姓名</th>
                    <th scope="col">Email</th>
                    <th scope="col">會員電話</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?= $r2['member_sid'] ?></td>
                    <td><?= $r2['member_name'] ?></td>
                    <td><?= $r2['member_email'] ?></td>
                    <td><?= $r2['member_mobile'] ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="btn btn-primary" id="sendCoupon">確定發送</div>
    <div class="alert alert-danger" role="alert" id="infoBar" style="display:none"></div>
</div>

<?php include './partsNOEDIT/script.php' ?>
<script>
    const sendBtn = document.querySelector("#sendCoupon");
    const infoBar = document.querySelector('#infoBar');
    sendBtn.addEventListener("click", function() {
        fetch('m_coupon_send_add-api.php?coupon_sid=<?= $coupon_sid ?>&member_sid=<?= $member_sid ?>')
            .then(r => r.json())
            .then(obj => {
                console.log(obj);
                if (obj.success) {
                    infoBar.classList.remove('alert-danger')
                    infoBar.classList.add('alert-success')
                    infoBar.innerHTML = '發送成功'
                    infoBar.style.display = 'block';

                } else {
                    infoBar.classList.remove('alert-success')
                    infoBar.classList.add('alert-danger')
                    infoBar.innerHTML = '發送失敗'
                    infoBar.style.display = 'block';
                }
                setTimeout(() => {
                    location.href = 'm_coupon_send_list.php';
                }, 2000);
            })
    })
</script>
<?php include './partsNOEDIT/html-foot.php' ?>