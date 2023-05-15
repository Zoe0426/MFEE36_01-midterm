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
                        <option value="2">姓名</option>
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
        </div>
    </div>
    <div class="row"></div>
</div>

<?php include './partsNOEDIT/script.php' ?>
<script>
    const oOrdersTable = document.getElementById('oOrdersTable');
    // ====取得訂單資料====
    function getMemOrd(e) {
        e.preventDefault();
        const orderSidInput = document.getElementById('sbOrder');
        const nameInput = document.getElementById('sbname');
        const mobileInput = document.getElementById('sbmobile');
        const memsidInput = document.getElementById('sbmemsid');
        oOrdersTable.innerHTML = '';
        if (nameInput.value || mobileInput.value || memsidInput.value || orderSidInput) {
            const fd = new FormData(document.getElementById('oGetmem'));
            fetch('o-api02_1_getMemOrders.php', {
                    method: 'POST',
                    body: fd,
                }).then(r => r.json())
                .then(obj => {
                    console.log(obj.getBy);
                    if (obj.getBy == 'orderSid') {
                        tableByOrderSid(obj);
                    } else {
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
        const orderSidTable = document.createElement('div');
        let orderStatus = "";
        let post_Status = "";
        let post_type = "";
        obj.order_status == 0 ? orderStatus = "未付款" : orderStatus = "已付款";
        obj.postType == 1 ? post_type = "寄送到府" : post_type = "超市領取";
        if (obj.postStatus == 0) {
            post_Status = '已領取';
        } else if (obj.postStatus == 1) {
            post_Status = '備貨中';
        } else if (obj.postStatus == 2) {
            post_Status = '運送中';
        } else if (obj.postStatus == 3) {
            post_Status = '貨到超商';
        } else if (obj.postStatus == 4) {
            post_Status = '未領取';
        }

        orderSidTable.innerHTML = `<table class="table table-striped ocd">
                <thead>
                    <tr>
                        <th scope="col">訂單編號</th>
                        <th scope="col">會員編號</th>
                        <th scope="col">訂單狀態</th>
                        <th scope="col">優惠券編號</th>
                        <th scope="col">寄送方式</th>
                        <th scope="col">寄送狀態</th>
                        <th scope="col">訂單成立時間</th>
                        <th scope="col"><i class="fa-regular fa-file-lines"></i></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row">${obj.order_sid}</th>
                        <td>${obj.member_sid}</td>
                        <td>${orderStatus}</td>
                        <td>${obj.coupon_sid}</td>
                        <td>${post_type}</td>
                        <td>${post_Status}</td>
                        <td>${obj.createDt}</td>
                        <td><i class="fa-regular fa-file-lines text-success" onclick="showDetails(${obj.order_sid})"></i></td>
                    </tr>
                </tbody>
            </table>`;
        oOrdersTable.append(orderSidTable);

    }
    // ====顯示某員的所有訂單
    function tableByMemInfo(obj) {
        const mixTable = document.createElement('div');
        let displayItems;
        if (obj.getBy == "memName") {
            displayItems = obj.name_orders;
        } else if (obj.getBy == "memMobile") {
            displayItems = obj.mobile_orders;
        } else if (obj.getBy == "memSid") {
            displayItems = obj.sid_orders;
        }

        let orderStatus = "";
        let post_Status = "";
        let post_type = "";
        let items;

        for (let i = 0, len = displayItems.length; i < len; i++) {
            displayItems[i].order_status == 0 ? orderStatus = "未付款" : orderStatus = "已付款";
            displayItems[i].postType == 1 ? post_type = "寄送到府" : post_type = "超市領取";
            if (displayItems[i].postStatus == 0) {
                post_Status = '已領取';
            } else if (displayItems[i].postStatus == 1) {
                post_Status = '備貨中';
            } else if (displayItems[i].postStatus == 2) {
                post_Status = '運送中';
            } else if (displayItems[i].postStatus == 3) {
                post_Status = '貨到超商';
            } else if (displayItems[i].postStatus == 4) {
                post_Status = '未領取';
            }
            items += `<tr>
                        <th scope="row">${displayItems[i].order_sid}</th>
                        <td>${displayItems[i].member_sid}</td>
                        <td>${orderStatus}</td>
                        <td>${displayItems[i].coupon_sid}</td>
                        <td>${post_type}</td>
                        <td>${post_Status}</td>
                        <td>${displayItems[i].createDt}</td>
                        <td><i class="fa-regular fa-file-lines text-success" onclick="showDetails(${displayItems[i].order_sid})"></i></td>
                    </tr>`
        }
        mixTable.innerHTML = `<table class="table table-striped ocd">
                <thead>
                    <tr>
                        <th scope="col">訂單編號</th>
                        <th scope="col">會員編號</th>
                        <th scope="col">訂單狀態</th>
                        <th scope="col">優惠券編號</th>
                        <th scope="col">寄送方式</th>
                        <th scope="col">寄送狀態</th>
                        <th scope="col">訂單成立時間</th>
                        <th scope="col"><i class="fa-regular fa-file-lines"></i></th>
                    </tr>
                </thead>
                <tbody>
                    ${items}
                </tbody>
            </table>`;
        oOrdersTable.append(mixTable);
    }
    //顯示訂單明細
    function showDetails() {

    }
</script>
<?php include './partsNOEDIT/html-foot.php' ?>