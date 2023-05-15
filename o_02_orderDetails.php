<?php
require './partsNOEDIT/connect-db.php' ?>
<?php include './partsNOEDIT/html-head.php' ?>
<style>
    .ocd {
        border: 2px dashed #ffc107;
        border-collapse: collapse;
    }

    .o-d-none {
        display: none;
    }
</style>
<?php include './partsNOEDIT/navbar.php' ?>
<div class="container pt-4">
    <!-- =====選擇會員===== -->
    <form id="oGetmem" onsubmit="getMemOrd(event)">
        <div class="container-fluid">
            <div class="row g-0">
                <div class="col-4">
                    <select class="form-select" aria-label="Default select example" name="searchBy" onchange="searchm(event)">
                        <option selected value="1">訂單編號</option>
                        <option selected value="2">姓名</option>
                        <option value="3">手機</option>
                        <option value="4">會員編號</option>
                    </select>
                </div>
                <div class="col-4">
                    <input type="text" class="form-control mx-2" id="sbOrder" name="sbOrder">
                    <input type="text" class="form-control mx-2 o-d-none" id="sbname" name="sbname">
                    <input type="text" class="form-control mx-2 o-d-none" id="sbmobile" name="sbmobile">
                    <input type="text" class="form-control mx-2 o-d-none" id="sbmemsid" name="sbmemsid">
                </div>
                <div class="col-2">
                    <button type="submit" class="btn btn-warning ">搜尋</button>
                </div>
            </div>
            <div class="col-10 o-mem-table"> </div>
        </div>
    </form>

    <div class="row">
        <div class="col-12 pt-3" id="oOrdersTable">
            <table class="table table-striped ocd">
                <thead>
                    <tr>
                        <th scope="col">訂單編號</th>
                        <th scope="col">會員編號</th>
                        <th scope="col">訂單狀態</th>
                        <th scope="col">優惠券編號</th>
                        <th scope="col">寄送方式</th>
                        <th scope="col">寄送狀態</th>
                        <th scope="col">訂單成立時間</th>
                        <th scope="col"><i class="fa-regular fa-pen-to-square"></i></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row">1</th>
                        <td>Mark</td>
                        <td>Otto</td>
                        <td>@mdo</td>
                        <td>@mdo</td>
                        <td>@mdo</td>
                        <td>@mdo</td>
                        <td><i class="fa-regular fa-pen-to-square"></i></td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
    <div class="row"></div>
</div>

<?php include './partsNOEDIT/script.php' ?>
<script>
    // ====取得訂單資料====
    function getMemOrd(e) {
        e.preventDefault();
        const orderSidInput = document.getElementById('sbOrder');
        const nameInput = document.getElementById('sbname');
        const mobileInput = document.getElementById('sbmobile');
        const memsidInput = document.getElementById('sbmemsid');

        if (nameInput.value || mobileInput.value || memsidInput.value || orderSidInput) {
            const fd = new FormData(document.getElementById('oGetmem'));
            fetch('o-api02_1_getMemOrders.php', {
                    method: 'POST',
                    body: fd,
                }).then(r => r.json())
                .then(obj => {
                    if (obj.getby === 'orderSid') {
                        // console.log("getby Order");
                        tableByOrderSid(obj);
                    } else {
                        // console.log('getby other');
                        tableByMemInfo(obj);
                    }
                    console.log(obj);
                })
                .catch(ex => {
                    console.log(ex);
                })
        } else {
            //有空加訊息
            console.log('None of the inputs have a value');
        }

    }
    // ====搜尋顯示哪種input====
    function searchm(e) {
        const sbOrder = document.getElementById("sbOrder");
        const sbname = document.getElementById("sbname");
        const sbmobile = document.getElementById("sbmobile");
        const sbmemsid = document.getElementById("sbmemsid");
        let sb = e.target.value;
        sbOrder.style.display = "none";
        sbname.style.display = "none";
        sbmobile.style.display = "none";
        sbmemsid.style.display = "none";
        if (sb === "1") {
            sbOrder.style.display = "block";
        } else if (sb === "2") {
            sbname.style.display = "block";
        } else if (sb === "3") {
            sbmobile.style.display = "block";
        } else if (sb === "4") {
            sbmemsid.style.display = "block";
        }
    }
    //====顯示會員資料====
    function showMemInfo(obj) {
        let oMemTb = document.querySelector(".o-mem-table");
        oMemTb.innerHTML = `
                <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">會員編號</th>
                                <th scope="col">姓名</th>
                                <th scope="col">電話</th>
                                <th scope="col">生日</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">${obj.sid}</th>
                                <td>${obj.name}</td>
                                <td>${obj.mobile}</td>
                                <td>${obj.birth}</td>
                            </tr>
                        </tbody>
                    </table>`;
    }
    // ====顯示指定訂單
    function tableByOrderSid(obj) {

    }
    // ====顯示某員的所有訂單
    function tableByMemInfo(obj) {

    }
</script>
<?php include './partsNOEDIT/html-foot.php' ?>