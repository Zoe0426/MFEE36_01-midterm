<?php include './partsNOEDIT/html-head.php' ?>
<?php include './partsNOEDIT/navbar.php' ?>
<div class="container">
    <form name="m_coupon_add" onsubmit="coupon_add_form(event)">
        <div class="mb-3">
            <label for="coupon_code" class="form-label">代碼</label>
            <input type="text" class="form-control" id="coupon_code" aria-describedby="emailHelp" name="coupon_code">
            <div id="form-text" class="form-text"></div>
        </div>
        <div class="mb-3">
            <label for="coupon_name" class="form-label">優惠券名稱</label>
            <input type="text" class="form-control" id="coupon_name" aria-describedby="emailHelp" name="coupon_name">
            <div id="form-text" class="form-text"></div>
        </div>
        <div class="mb-3">
            <label for="coupon_price" class="form-label">優惠券金額</label>
            <input type="text" class="form-control" id="coupon_price" aria-describedby="emailHelp" name="coupon_price">
            <div id="form-text" class="form-text"></div>
        </div>
        <div class="mb-3">
            <label for="coupon_startDate" class="form-label">開始日</label>
            <input type="date" class="form-control" id="coupon_startDate" aria-describedby="emailHelp" name="coupon_startDate">
            <div id="form-text" class="form-text"></div>
        </div>
        <div class="mb-3">
            <label for="coupon_expDate" class="form-label">結束日</label>
            <input type="date" class="form-control" id="coupon_expDate" aria-describedby="emailHelp" name="coupon_expDate">
            <div id="form-text" class="form-text"></div>
        </div>
        <div class="alert alert-danger" role="alert" id="infoBar" style="display:none"></div>
        <button type="submit" class="btn btn-primary">新增</button>
    </form>
</div>

<?php include './partsNOEDIT/script.php' ?>
<script>
    function coupon_add_form(event) {
        event.preventDefault();

        const fd = new FormData(document.m_coupon_add);
        fetch('m_coupon_type_add-api.php', {
                method: 'POST',
                body: fd,
            }).then(r => r.json())
            .then(obj => {
                console.log(obj);
            })
    }
</script>
<?php include './partsNOEDIT/html-foot.php' ?>