<?php
require './partsNOEDIT/connect-db.php';

$sid = isset($_GET["coupon_sid"]) ? $_GET["coupon_sid"] : '';
$sql = "SELECT * FROM mem_coupon_type WHERE coupon_sid=:sid";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':sid', $sid, PDO::PARAM_STR);
$stmt->execute();
$r = $stmt->fetch(PDO::FETCH_ASSOC);



?>

<?php include './partsNOEDIT/html-head.php' ?>
<?php include './partsNOEDIT/navbar.php' ?>
<div class="container">
    <form name="m_coupon_update" onsubmit="coupon_update_form(event)">
        <input type="hidden" class="form-control" id="coupon_sid" aria-describedby="emailHelp" name="coupon_sid" value="<?= $r['coupon_sid'] ?>">
        <div class="mb-3">
            <label for="coupon_code" class="form-label">代碼</label>
            <input type="text" class="form-control" id="coupon_code" aria-describedby="emailHelp" name="coupon_code" value="<?= $r['coupon_code'] ?>">
            <div id="form-text" class="form-text"></div>
        </div>
        <div class="mb-3">
            <label for="coupon_name" class="form-label">優惠券名稱</label>
            <input type="text" class="form-control" id="coupon_name" aria-describedby="emailHelp" name="coupon_name" value="<?= $r['coupon_name'] ?>">
            <div id="form-text" class="form-text"></div>
        </div>
        <div class="mb-3">
            <label for="coupon_price" class="form-label">優惠券金額</label>
            <input type="text" class="form-control" id="coupon_price" aria-describedby="emailHelp" name="coupon_price" value="<?= $r['coupon_price'] ?>">
            <div id="form-text" class="form-text"></div>
        </div>
        <div class="mb-3">
            <label for="coupon_startDate" class="form-label">開始日</label>
            <input type="date" class="form-control" id="coupon_startDate" aria-describedby="emailHelp" name="coupon_startDate" value="<?= $r['coupon_startDate'] ?>">
            <div id="form-text" class="form-text"></div>
        </div>
        <div class="mb-3">
            <label for="coupon_expDate" class="form-label">結束日</label>
            <input type="date" class="form-control" id="coupon_expDate" aria-describedby="emailHelp" name="coupon_expDate" value="<?= $r['coupon_expDate'] ?>">
            <div id="form-text" class="form-text"></div>
        </div>
        <div class="alert alert-danger" role="alert" id="infoBar" style="display:none"></div>
        <button type="submit" class="btn btn-primary">修改</button>
    </form>
</div>

<?php include './partsNOEDIT/script.php' ?>
<script>
    function coupon_update_form(event) {
        event.preventDefault();

        const fd = new FormData(document.m_coupon_update);
        fetch('m_couon_type_update-api.php', {
                method: 'POST',
                body: fd,
            }).then(r => r.json())
            .then(obj => {
                console.log(obj);
            })
    }
</script>
<?php include './partsNOEDIT/html-foot.php' ?>