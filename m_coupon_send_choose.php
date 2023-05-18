<?php
require './partsNOEDIT/connect-db.php';
$sql_coupon_name = "SELECT *  FROM mem_coupon_type";
$r_coupon_name = $pdo->query($sql_coupon_name)->fetchAll();

?>


<?php include './partsNOEDIT/html-head.php' ?>
<?php include './partsNOEDIT/navbar.php' ?>
<style>
    form .mb-3 .form-text {
        color: red;
    }
</style>
<div class="container">
    <form name="m_coupon_send_info" onsubmit="coupon_send_detail(event)">
        <div class="mb-3">
            <label for="coupon_name" class="form-label">優惠券名稱</label>
            <select name="coupon_sid" id="coupon_sid" data-required="1">
                <option value="">--請選擇--</option>
                <?php foreach ($r_coupon_name as $i) : ?>
                    <option value="<?= $i['coupon_sid'] ?> "><?= $i['coupon_name'] ?></option>
                <?php endforeach; ?>
            </select>
            <div id="form-text" class="form-text"></div>
        </div>
        <div class="mb-3">
            <label for="member_sid" class="form-label">會員編號</label>
            <input type="text" class="form-control" id="member_sid" aria-describedby="emailHelp" name="member_sid" data-required="1">
            <div id="form-text" class="form-text"></div>
        </div>
        <div class="alert alert-danger" role="alert" id="infoBar" style="display:none"></div>
        <button type="button" id="confirm">確認細節</button>
    </form>
</div>

<?php include './partsNOEDIT/script.php' ?>
<script>
    const infoBar = document.querySelector('#infoBar');
    const fields = document.querySelectorAll('form *[data-required="1"]');

    function coupon_send_detail(event) {
        event.preventDefault();
        for (let f of fields) {
            f.style.border = '1px solid #ccc';
            f.nextElementSibling.innerHTML = ''
        }


        let isPass = true; // 預設值是通過的

        // TODO: 檢查欄位資料
        for (let f of fields) {
            if (!f.value) {
                isPass = false;
                f.style.border = '1px solid red';
                f.nextElementSibling.innerHTML = '請填入資料'
            }
        }
    }

    const confirmBtn = document.querySelector("#confirm");
    const coupon_sid = document.querySelector("#coupon_sid");
    const member_sid = document.querySelector("#member_sid");

    confirmBtn.addEventListener("click", function() {
        location.href = `m_coupon_send_info.php?coupon_sid=${coupon_sid.value}&member_sid=${member_sid.value}`
        // console.log(coupon_sid.value);
        // console.log(member_sid.value);
    })
</script>
<?php include './partsNOEDIT/html-foot.php' ?>