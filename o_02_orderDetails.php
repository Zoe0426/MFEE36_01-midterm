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
                <p class="fs-5 fw-bold">訂單管理系統</p>
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
    <!-- =====顯示訂單/明細===== -->
    <div class="col-12 pt-3" id="oOrdersTable">
    </div>

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
                    showMemInfo(obj);

                    if (obj.getBy == 'orderSid') {
                        tableByOrderSid(obj);
                    } else {
                        tableByMemInfo(obj);
                    }

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
        let oMemDetails = '';
        if (obj.getBy == "memName") {
            oMemDetails = obj.name_orders[0];
        } else if (obj.getBy == "memMobile") {
            oMemDetails = obj.mobile_orders[0];
        } else if (obj.getBy == "memSid") {
            oMemDetails = obj.sid_orders[0];
        } else if (obj.getBy == 'orderSid') {
            oMemDetails = obj;
        }

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
                                <th scope="row">${oMemDetails.member_sid}</th>
                                <td>${oMemDetails.member_name}</td>
                                <td>${oMemDetails.member_mobile}</td>
                                <td>${oMemDetails.member_birth}</td>
                            </tr>
                        </tbody>
                    </table>`;
    }
    // ====顯示指定訂單====
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
                        <td><i class="fa-regular fa-file-lines text-success" onclick="showDetails('${obj.order_sid}',this)"></i></td>
                    </tr> 
                    <tr style="display:none;">
                       
                    </tr>
                </tbody>
            </table>`;
        oOrdersTable.append(orderSidTable);

    }
    // ====顯示某員的所有訂單====
    function tableByMemInfo(obj) {
        const mixTable = document.createElement('div');
        // mixTable.classList.add('test');
        let displayItems; //所有訂單的容(父)
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
        let items = "";
        //show all orders(parent)
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
            } else {
                console.log('postStatus unknown');
            }
            items += `<tr">
                        <th scope="row">${displayItems[i].order_sid}</th>
                        <td>${displayItems[i].member_sid}</td>
                        <td>${orderStatus}</td>
                        <td>${displayItems[i].coupon_sid}</td>
                        <td>${post_type}</td>
                        <td>${post_Status}</td>
                        <td>${displayItems[i].createDt}</td>
                        <td><i class="fa-regular fa-file-lines text-success" onclick="showDetails('${displayItems[i].order_sid}', this)"></i></td>
                    </tr>
                    <tr style="display:none;"></tr>`;
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
    // ====顯示訂單明細====
    function showDetails(ord, x) {
        fetch(`o-api02_2_getOrderDetails.php?orderSid=${ord}`)
            .then(r => r.json())
            .then(objectD => {
                let objArray = objectD.order_details;
                let detailsRow = x.parentElement.parentElement.nextElementSibling;

                if (detailsRow.innerHTML !== "") {
                    detailsRow.innerHTML = "";
                }
                console.log('detailsRow:');
                console.log(detailsRow);


                let dRowsTd = ""
                for (let obj of objArray) {
                    let rel_type = "";
                    let pAmount = "";
                    let qty = "";
                    let adAmount = "";
                    let adQty = "";
                    let kidAmount = "";
                    let kidQty = "";

                    obj.relType == 'prod' ? rel_type = "商城" : rel_type = "活動";
                    obj.prodAmount == null ? pAmount = 0 : pAmount = obj.prodAmount;
                    obj.prodQty == null ? qty = 0 : qty = obj.prodQty;
                    obj.adultAmount == null ? adAmount = 0 : adAmount = obj.adultAmount;
                    obj.adultQty == null ? adQty = 0 : adQty = obj.adultQty;
                    obj.childAmount == null ? kidAmount = 0 : kidAmount = obj.childAmount;
                    obj.childQty == null ? kidQty = 0 : kidQty = obj.childQty;
                    dRowsTd += `<tr>
                            <td>${rel_type}</td>
                            <td>${obj.rel_sid}</td>
                            <td>${obj.rel_seq_sid}</td>
                            <td>${obj.relName}</td>
                            <td>${obj.rel_seqName}</td>
                            <td>${pAmount}</td>
                            <td>${qty}</td>
                            <td>${adAmount}</td>
                            <td>${adQty}</td>
                            <td>${kidAmount}</td>
                            <td>${kidQty}</td>
                            <td>${obj.amount}</td>
                        </tr>`
                }
                detailsRow.innerHTML += `<td colspan="8">
                        <table class="table table-bordered table-light">
                                <thead>
                                    <tr>
                                        <th scope="col">明細來源</th>
                                        <th scope="col">產品編號</th>
                                        <th scope="col">品項編號</th>
                                        <th scope="col">產品名稱</th>
                                        <th scope="col">規格/期別</th>
                                        <th scope="col">商品單價</th>
                                        <th scope="col">數量</th>
                                        <th scope="col">成人單價</th>
                                        <th scope="col">人數(ad)</th>
                                        <th scope="col">兒童單價</th>
                                        <th scope="col">人數(kid)</th>
                                        <th scope="col">小計</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   ${dRowsTd}
                                </tbody>
                            </table>
                        </td>`;

                if (detailsRow.style.display === 'none') {
                    detailsRow.style.display = 'table-row';
                } else {
                    detailsRow.style.display = 'none';
                }
            })
            .catch(ex => {
                console.log(ex);
            })
    }
</script>
<?php include './partsNOEDIT/html-foot.php' ?>