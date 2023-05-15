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
    <form name="m_coupon_send_info" onsubmit="coupon_send_info(event)">
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
        <button type="submit" class="btn btn-primary">確定發送</button>
    </form>
</div>

<?php include './partsNOEDIT/script.php' ?>
<script>
    const infoBar = document.querySelector('#infoBar');
    const fields = document.querySelectorAll('form *[data-required="1"]');

    function coupon_send_info(event) {
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
        const fd = new FormData(document.m_coupon_send_info);
        fetch('m_coupon_send_info-api.php', {
                method: 'POST',
                body: fd,
            }).then(r => r.json())
            .then(obj => {
                console.log(obj);
                if (obj.success) {
                    infoBar.classList.remove('alert-danger');
                    infoBar.classList.add('alert-success');
                    infoBar.innerHTML = '發送成功';
                    infoBar.style.display = 'block';
                    setTimeout(() => {
                        location.href = 'm_coupon_send_list.php';
                    }, 1000)
                } else {

                }
            })
    }
</script>
<?php include './partsNOEDIT/html-foot.php' ?>