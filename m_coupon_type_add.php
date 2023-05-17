<?php include './partsNOEDIT/html-head.php' ?>
<?php include './partsNOEDIT/navbar.php' ?>
<style>
    form .mb-3 .form-text {
        color: red;
    }
</style>
<div class="container">
    <form name="m_coupon_add" onsubmit="coupon_add_form(event)">
        <div class="mb-3">
            <label for="coupon_code" class="form-label">代碼</label>
            <input type="text" class="form-control" id="coupon_code" aria-describedby="emailHelp" name="coupon_code" data-required="1">
            <div id="form-text" class="form-text"></div>
        </div>
        <div class="mb-3">
            <label for="coupon_name" class="form-label">優惠券名稱</label>
            <input type="text" class="form-control" id="coupon_name" aria-describedby="emailHelp" name="coupon_name" data-required="1">
            <div id="form-text" class="form-text"></div>
        </div>
        <div class="mb-3">
            <label for="coupon_price" class="form-label">優惠券金額</label>
            <input type="text" class="form-control" id="coupon_price" aria-describedby="emailHelp" name="coupon_price" data-required="1">
            <div id="form-text" class="form-text"></div>
        </div>
        <div class="mb-3">
            <label for="coupon_startDate" class="form-label">開始日</label>
            <input type="date" class="form-control" id="coupon_startDate" aria-describedby="emailHelp" name="coupon_startDate" data-required="1" onchange="setMinEndDate()">
            <div id="form-text" class="form-text"></div>
        </div>
        <div class="mb-3">
            <label for="coupon_expDate" class="form-label">結束日</label>
            <input type="date" class="form-control" id="coupon_expDate" aria-describedby="emailHelp" name="coupon_expDate" data-required="1" onchange="setMaxEndDate()">
            <div id="form-text" class="form-text"></div>
        </div>
        <div class="alert alert-danger" role="alert" id="infoBar" style="display:none"></div>
        <button type="submit" class="btn btn-primary">新增</button>
    </form>
</div>

<?php include './partsNOEDIT/script.php' ?>
<script>
    const infoBar = document.querySelector('#infoBar');
    const fields = document.querySelectorAll('form *[data-required="1"]');

    function coupon_add_form(event) {
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
        const fd = new FormData(document.m_coupon_add);
        fetch('m_coupon_type_add-api.php', {
                method: 'POST',
                body: fd,
            }).then(r => r.json())
            .then(obj => {
                console.log(obj);
                if (obj.success) {
                    infoBar.classList.remove('alert-danger');
                    infoBar.classList.add('alert-success');
                    infoBar.innerHTML = '登入成功';
                    infoBar.style.display = 'block';
                    setTimeout(() => {
                        location.href = 'm_coupon_type-list.php';
                    }, 1000)
                } else {

                }
            })
    }

    function setMinEndDate() {
        // 獲取開始日期元素
        const startDateInput = document.querySelector("#coupon_startDate");
        // 將結束日期的min屬性設置為開始日期
        document.querySelector("#coupon_expDate").setAttribute("min", startDateInput.value);
    }

    function setMaxEndDate() {
        // 獲取結束日期元素
        const expDateInput = document.querySelector("#coupon_expDate");
        // 將結束日期的min屬性設置為開始日期
        document.querySelector("#coupon_startDate").setAttribute("max", expDateInput.value);
    }
</script>
<?php include './partsNOEDIT/html-foot.php' ?>