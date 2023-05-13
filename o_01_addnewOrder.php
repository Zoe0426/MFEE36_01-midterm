<?php
require './partsNOEDIT/connect-db.php' ?>
<?php include './partsNOEDIT/html-head.php' ?>
<style>
    #oGetItemsForm input[type="number"] {
        width: 60px;
    }

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
    <form id="oGetmem" onsubmit="getMemCart(event)">
        <div class="container-fluid">
            <div class="row g-0">
                <div class="col-4">
                    <select class="form-select" aria-label="Default select example" name="searchBy" onchange="searchm(event)">
                        <option selected value="1">姓名</option>
                        <option value="2">手機</option>
                        <option value="3">會員編號</option>
                    </select>
                </div>
                <div class="col-4">
                    <input type="text" class="form-control mx-2" id="sbname" name="sbname">
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
    <!-- =====顯示購物車內容===== -->
    <form id="oGetItemsForm" onsubmit="createOrder(event)">
        <div class="container">
            <div class="row">
                <div class="col-10">
                    <div id="oCartDisplay">
                    </div>
                    <div id="oPostPayDisplay" class="container-fluid">
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-warning ">成立訂單</button>
    </form>
</div>
</div>
<?php include './partsNOEDIT/script.php' ?>
<script>
    // ====拿XX會員的個人資料,購物車內容,及coupon====
    function getMemCart(e) {
        e.preventDefault();
        const fd = new FormData(document.getElementById('oGetmem'));
        fetch('o_api01_getMemCart.php', {
                method: 'POST',
                body: fd,
            }).then(r => r.json())
            .then(obj => {
                console.log(obj);
                showMemInfo(obj);
                showCartnCou(obj);
                showPostnPay();
            })
            .catch(ex => {
                console.log(ex);
            })
    }
    // ====成立訂單====
    function createOrder(e) {
        e.preventDefault();
        const newodfd = new FormData(document.getElementById("oGetItemsForm"));
        console.log(newodfd);
        fetch('o_api02_newOrder.php', {
                method: 'POST',
                body: newodfd,
            }).then(r => r.json())
            .then(obj => {
                console.log(obj);

            })
            .catch(ex => {
                console.log(ex);
            })
    }
    // ====搜尋顯示哪種input====
    function searchm(e) {
        const sbname = document.getElementById("sbname");
        const sbmobile = document.getElementById("sbmobile");
        const sbmemsid = document.getElementById("sbmemsid");
        let sb = e.target.value;
        sbname.style.display = "none";
        sbmobile.style.display = "none";
        sbmemsid.style.display = "none";
        if (sb === "1") {
            sbname.style.display = "block";
        } else if (sb === "2") {
            sbmobile.style.display = "block";
        } else if (sb === "3") {
            sbmemsid.style.display = "block";
        }
    }
    //====選擇所有商品====
    function selectAllProducts() {
        console.log('clicked');
        const shopAllCheckbox = document.querySelector('input[name="shopAll"]');
        const prodCheckboxes = document.querySelectorAll('input[name="prod"]');

        if (shopAllCheckbox.checked) {
            prodCheckboxes.forEach((checkbox) => {
                checkbox.checked = true;
            });
        } else {
            prodCheckboxes.forEach((checkbox) => {
                checkbox.checked = false;
            });
        }
    }
    //====選擇所有活動====
    function selectAllActs() {
        console.log('clicked');
        const actAllCheckbox = document.querySelector('input[name="actAll"]');
        const actCheckboxes = document.querySelectorAll('input[name="act"]');

        if (actAllCheckbox.checked) {
            actCheckboxes.forEach((checkbox) => {
                checkbox.checked = true;
            });
        } else {
            actCheckboxes.forEach((checkbox) => {
                checkbox.checked = false;
            });
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
    //====顯示購物車內容及coupon
    function showCartnCou(obj) {
        let oCartDisplay = document.getElementById("oCartDisplay");

        //===商品table 
        let ost = document.createElement("div");
        let sData = obj.shoplist;
        let shopContent = "";
        for (let i = 0; i < sData.length; i++) {
            shopContent +=
                `<tr>
                    <td><input class="form-check-input" type="checkbox" value="[${sData[i].pro_sid},${sData[i].proDet_sid}]" name="prod[]"></td>
                    <td>${sData[i].pro_sid}-${sData[i].proDet_sid}</td>
                    <td>${sData[i].pro_name}</td>
                    <td>${sData[i].proDet_name}</td>
                    <td><input type="number" id="oMqty${i}" min="0" value="${sData[i].prodQty}"></td>
                    <td>${sData[i].proDet_price}</td>
                    <td id="oSstock${i}">${sData[i].proDet_qty}</td>
                </tr>`;
        }
        ost.innerHTML = `<table class="ocd table table-border table-striped">
                <thead>
                    <tr>
                        <th scope="col"><input class="form-check-input" type="checkbox" name="shopAll" id="oshopAll" onchange="selectAllProducts()"></th>
                        <th scope="col">商品編號</th>
                        <th scope="col">商品名稱</th>
                        <th scope="col">品項</th>
                        <th scope="col">數量</th>
                        <th scope="col">單價</th>
                        <th scope="col">庫存</th>
                    </tr>
                </thead>
                <tbody>  
                ${shopContent}
                </tbody>
            </table>`;
        oCartDisplay.append(ost);

        //===活動table
        let oat = document.createElement("div");
        let aData = obj.actlist;
        console.log(aData);
        let actContent = "";
        for (let i = 0; i < aData.length; i++) {
            actContent +=
                `<tr>
                <td> <input class="form-check-input" type="checkbox" value="[${aData[i].act_sid},${aData[i].group_sid}]" name="act[]"></td>
                <td>${aData[i].act_sid}</td>
                <td>${aData[i].act_name}</td>
                <td>${aData[i].group_date}</td>
                <td><input type="number" min="0" id="oPaqty${i}" value="${aData[i].adultQty}"></td>
                <td>${aData[i].price_adult}</td>
                <td><input type="number" min="0" id="oPcqty${i}" value="${aData[i].childQty}"></td>
                <td>${aData[i].price_kid}</td>
                <td id="oAstock${i}">${aData[i].ppl_max}</td>
            </tr>`;
        }
        oat.innerHTML = `
            <table class="ocd table table-border table-striped">
                <thead>
                    <tr>
                        <th scope="col"><input class="form-check-input" type="checkbox" name="actAll" id="oActAll" onchange="selectAllActs()"></th>
                        <th scope="col">活動編號</th>
                        <th scope="col">活動名稱</th>
                        <th scope="col">期別</th>
                        <th scope="col">人數(成人)</th>
                        <th scope="col">單價(成人)</th>
                        <th scope="col">人數(小孩)</th>
                        <th scope="col">單價(小孩)</th>
                        <th scope="col">剩餘名額</th>
                    </tr>
                </thead>
                <tbody>
                ${actContent}
                </tbody>
            </table>`;
        oCartDisplay.append(oat);

        //===優惠coupon
        let oct = document.createElement("div");
        let cData = obj.coupons;
        console.log(cData);
        let couContent = "";
        for (let i = 0; i < cData.length; i++) {
            couContent +=
                `<tr>
                    <td> <input class="form-check-input" type="radio" value="${cData[i].coupon_sid}" name="coupon"></td>
                    <td>${cData[i].coupon_code}</td>
                    <td>${cData[i].coupon_name}</td>
                    <td>$${cData[i].coupon_price}</td>
                    <td>${(cData[i].coupon_expDate).slice(0,10)}</td>
                </tr>`;
        }
        oct.innerHTML =
            `<table class="ocd table table-border table-striped">
                        <thead>
                            <tr>
                                <th scope="col"></th>
                                <th scope="col">優惠券編號</th>
                                <th scope="col">優惠券名稱</th>
                                <th scope="col">優惠金額</th>
                                <th scope="col">使用期限</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${couContent}
                        </tbody>
                    </table>`;
        oCartDisplay.append(oct);
    }
    //====顯示地址及付款方式
    function showPostnPay() {
        const oPostPayDisplay = document.querySelector("#oPostPayDisplay");
        const opp = document.createElement('div');
        opp.innerHTML =
            `<div class="postInfo row g-0 ">
            <div class="mb-3 col-4 px-0 me-3">
                <label for="postName" class="form-label">收件人姓名：</label>
                <input type="text" class="form-control" id="postName" name="postName" value="">
                <div id="oNameErrMsg" class="form-text d-none"></div>
            </div>
            <div class="mb-3 col-4 me-3">
                <label for="postMob" class="form-label">手機號碼：</label>
                <input type="text" class="form-control" id="postMob" name="postMobile" value="">
                <div id="oMobErrMsg" class="form-text d-none"></div>
            </div>
            <div class="mb-3 col-10">
                <label for="address" class="form-label">寄送地址：</label>
                <input type="text" class="form-control" id="address" name="address" value="">
                <div id="oAddErrMsg" class="form-text d-none"></div>
            </div>
        </div>`;
        oPostPayDisplay.classList.add("ocd");
        oPostPayDisplay.classList.add("p");
        oPostPayDisplay.append(opp);
    }
</script>
<?php include './partsNOEDIT/html-foot.php' ?>