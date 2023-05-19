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

    .oPriceInfo {
        position: sticky;
        top: 100px;
        right: 0;
        height: 350px;
        border: 2px dashed #ffc107;
        padding-top: 1rem;
    }
</style>
<?php include './partsNOEDIT/navbar.php' ?>
<div class="container pt-4">
    <!-- =====選擇會員===== -->
    <form id="oGetmem" onsubmit="getMemCart(event)">
        <div class="container-fluid">
            <div class="row g-0">
                <p class="fs-5 fw-bold text-secondary">購物車系統</p>
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
                <div class="col-2 ms-3">
                    <button type="submit" class="btn btn-warning ">搜尋</button>
                </div>
            </div>
            <div class="col-10 o-mem-table pt-3"> </div>
        </div>
    </form>
    <!-- =====顯示購物車內容===== -->
    <form id="oGetItemsForm" onsubmit="createOrder(event)">
        <div class="container">
            <div class="row">
                <div class="col-10">
                    <div id="oCartDisplay">
                    </div>
                    <div id="oPostPayDisplay" class="container-fluid px-0 bg-body-secondary">
                    </div>
                </div>
                <div id="totalPriceInfo" class="col-2 oPriceInfo pt-2 o-d-none">

                </div>
            </div>
        </div>
        <!-- Modal Send New Order -->
        <div class="modal fade" id="oConfirmNewOrder" tabindex="-1" aria-labelledby="oCnewOdLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="oCnewOdLabel">訂單成立</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        前往訂單列表？
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">留在此頁面</button>
                        <button type="button" class="btn btn-warning" onclick="toOrderDetailsPage()">前往訂單列表</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<?php include './partsNOEDIT/script.php' ?>
<script>
    const oCartDisplay = document.getElementById("oCartDisplay");
    const oPostPayDisplay = document.querySelector('#oPostPayDisplay');
    const oMemTb = document.querySelector(".o-mem-table");
    const send = document.createElement('div');
    const totalPriceInfo = document.querySelector('#totalPriceInfo')

    // ====GET DATA,CART,COUPON====
    function getMemCart(e) {
        e.preventDefault();
        const nameInput = document.getElementById('sbname');
        const mobileInput = document.getElementById('sbmobile');
        const memsidInput = document.getElementById('sbmemsid');

        if (nameInput.value || mobileInput.value || memsidInput.value) {
            const fd = new FormData(document.getElementById('oGetmem'));
            fetch('o_api01_1_getMemCart.php', {
                    method: 'POST',
                    body: fd,
                }).then(r => r.json())
                .then(obj => {
                    // console.log(obj);
                    oCartDisplay.innerHTML = "";
                    showMemInfo(obj);

                    if (obj.shoplist !== 'noShopItems') {
                        // console.log("showShop");
                        showShopList(obj);
                    }
                    if (obj.actlist !== 'noActItems') {
                        // console.log("showAct");
                        showActList(obj);
                    }
                    if (obj.coupons !== 'noCoupons') {
                        // console.log("showCoupon");
                        showCoupon(obj);
                    }
                    showPostnPay(obj);
                    const createOrderBtn = document.querySelector('.btn[data-bs-target="#oMsgToClient"]');
                    createOrderBtn.addEventListener('click', handleCreateOrder);
                    showTotalPriceInfo();
                    totalPriceInfo.style.display = "block";
                })
                .catch(ex => {
                    console.log(ex);
                })
        } else {
            //有空加訊息
            console.log('None of the inputs have a value');
        }

    }

    function handleCreateOrder() {
        let isPass = true;
        const InfoBar = document.querySelector("#oInfoBar");
        // const oMemTb = document.querySelector(".o-mem-table");
        const newodfd = new FormData(document.getElementById("oGetItemsForm"));
        let prods = newodfd.getAll("prod[]").length;
        let acts = newodfd.getAll("act[]").length;
        let postFormInputs = document.querySelectorAll(".postInfo input");

        //若沒選商品，就不過
        if (prods === 0 && acts === 0) {
            isPass = false;
        }
        //若沒填內容，就不過
        for (let i of postFormInputs) {
            if ((i.value).trim() === '') {
                isPass = false;
            }
        }

        if (isPass) { //若過了，觸發modal
            const modal = new bootstrap.Modal(document.getElementById('oMsgToClient'));
            modal.show();
        } else { //若不過，顯示訊息
            if (prods === 0 && acts === 0) {
                oInfoBar.style.display = "block";
                oInfoBar.innerHTML = "請選擇至少一件商品或活動";
                setTimeout(() => {
                    oInfoBar.style.display = "none";
                }, 3000);
            }
            for (let i of postFormInputs) {
                if ((i.value).trim() === '') {
                    i.style.border = '1px solid red';
                    i.style.display = 'block';
                    i.nextElementSibling.display = "block";
                    i.nextElementSibling.innerHTML = '此欄位必需填寫';
                    oInfoBar.innerHTML = '寄送資訊欄位不可空白';
                    setTimeout(() => {
                        i.style.border = "1px solid #ccc";
                        i.nextElementSibling.innerHTML = '';
                        i.nextElementSibling.display = "none";
                    }, 3000);
                }
            }
        }
    }
    // ====CREATE ORDER====
    function createOrder(e) {
        e.preventDefault();
        const newodfd = new FormData(document.getElementById("oGetItemsForm"));
        fetch('o_api01_2_newOrder.php', {
                method: 'POST',
                body: newodfd,
            }).then(r => r.json())
            .then(obj => {
                console.log(obj);
                if (obj.orderSuccess == true) {
                    oCartDisplay.innerHTML = '';
                    oPostPayDisplay.innerHTML = '';
                    oMemTb.innerHTML = '';
                    // oGetItemsForm.remove(send);
                }
            })
            .catch(ex => {
                console.log(ex);
            })
        send.innerHTML = '';
        totalPriceInfo.style.display = "none";
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
    //====顯示會員資料====
    function showMemInfo(obj) {
        // let oMemTb = document.querySelector(".o-mem-table");
        oMemTb.innerHTML = `
                <table class="table">
                        <thead class="bg-warning-subtle">
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
    //====顯示計算金額====
    function calAllTotalPrice() {
        const prodSubtotal = document.querySelector("#prodSubtotal");
        const actSubtotal = document.querySelector("#actSubtotal");
        const post = document.querySelector("#post");
        const couponPrice = document.querySelector('#couponPrice');
        const total = document.querySelector('#total');

        prodSubtotal.innerHTML = calProdTotalPrice();
        actSubtotal.innerHTML = calActTotalPrice();
        if (prodSubtotal.innerHTML == 0) {
            post.innerHTML = '0';
        } else {
            post.innerHTML = '80';
        }
        couponPrice.innerHTML = calCouponPrice();
        total.innerHTML = calProdTotalPrice() + calActTotalPrice() + (parseInt(post.innerHTML)) - calCouponPrice();


    }
    //====顯示總金額block====
    function showTotalPriceInfo() {
        totalPriceInfo.innerHTML = "";
        let priceBlock = document.createElement('div');
        priceBlock.innerHTML = `
                        <div class="d-flex justify-content-between">
                            <p>商城小計:</p>
                            <p class="fw-bold text-secondary">$<span id="prodSubtotal" class="fw-bold text-secondary">0</span></p>
                        </div>
                        <div class="d-flex justify-content-between">
                            <p>活動小計:</p>
                            <p class="fw-bold text-secondary">$<span id="actSubtotal" class="fw-bold text-secondary">0</span></p>
                        </div>
                        <div class="d-flex justify-content-between">
                            <p>郵寄金額:</p>
                            <p class="fw-bold text-secondary">$<span id="post" class="fw-bold text-secondary">0</span></p>
                        </div>
                        <div class="d-flex justify-content-between">
                            <p>優惠券金額:</p>
                            <p class="fw-bold text-secondary">-$<span id="couponPrice" class="fw-bold text-secondary">0</span></p>
                        </div>
                        <div class="d-flex justify-content-between">
                            <p>總金額:</p>
                            <p class="fw-bold text-danger-emphasis">$<span id="total" class="fw-bold text-danger-emphasis">0</span></p>
                        </div>`
        totalPriceInfo.append(priceBlock);
    }
    //====顯示商城商品
    function showShopList(obj) {
        let ost = document.createElement("div");
        let sData = obj.shoplist;
        let shopContent = "";
        for (let i = 0; i < sData.length; i++) {
            shopContent +=
                `<tr>
                    <td><input class="form-check-input" type="checkbox" value="${encodeURIComponent(JSON.stringify(sData[i]))}" name="prod[]"></td>
                    <td>${sData[i].pro_sid}-${sData[i].proDet_sid}</td>
                    <td>${sData[i].pro_name}</td>
                    <td>${sData[i].proDet_name}</td>
                    <td><input type="number" min="0" value="${sData[i].prodQty}" class="sProdQty"></td>
                    <td>${sData[i].proDet_price}</td>
                    <td totalStock="${sData[i].proDet_qty}" proSid="${sData[i].pro_sid}" proDet="${sData[i].proDet_sid}" class="text-secondary">${(sData[i].proDet_qty)-(sData[i].prodQty)}</td>

                    <td class="text-end" proSid="${sData[i].pro_sid}" proDet="${sData[i].proDet_sid}" mem="${obj.sid}" onclick="deleteCartItem('${obj.sid}','${sData[i].pro_sid}','${sData[i].proDet_sid}',this)"><i class="fa-solid fa-trash-can text-body-tertiary"></i></td>
                </tr>`;
        }

        ost.innerHTML = `<table class="ocd table table-border table-striped">
                <thead class="bg-danger-subtle">
                    <tr>
                        <th scope="col"><input class="form-check-input" type="checkbox" name="shopAll" onchange="selectAllProducts()"></th>
                        <th scope="col">商品編號</th>
                        <th scope="col">商品名稱</th>
                        <th scope="col">品項</th>
                        <th scope="col">數量</th>
                        <th scope="col">單價</th>
                        <th scope="col">庫存</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>  
                ${shopContent}
                </tbody>
            </table>`;
        oCartDisplay.append(ost);
        const sProdQties = document.querySelectorAll('.sProdQty');
        // console.log(sProdQties);
        for (let i of sProdQties) {
            i.addEventListener('change', sChangeStock);
        }
        //算商城總金額
        const sCheckboxes = document.querySelectorAll('input[name="prod[]"]');
        for (let i of sCheckboxes) {
            i.addEventListener('change', (event) => {
                calAllTotalPrice();
            });
        }

    }
    //====算商城被選取的總額
    function calProdTotalPrice() {
        const checkboxes = document.querySelectorAll('input[name="prod[]"]:checked');
        let totalPrice = 0;

        checkboxes.forEach((checkbox) => {
            const decodedValue = decodeURIComponent(checkbox.value);
            const product = JSON.parse(decodedValue);
            const price = parseFloat(product.proDet_price);
            const quantity = parseInt(product.prodQty);
            const productTotal = price * quantity;

            totalPrice += productTotal;
        });

        console.log('prod total : ' + totalPrice); // 输出价格总和，你可以根据需要进行进一步处理或显示

        return totalPrice;
    }
    //====顯示活動
    function showActList(obj) {
        let oat = document.createElement("div");
        let aData = obj.actlist;
        let actContent = "";
        for (let i = 0; i < aData.length; i++) {
            let astock = Math.floor(parseInt(aData[i].ppl_max) - (parseInt(aData[i].adultQty) + parseInt(aData[i].childQty) / 2));
            actContent +=
                `<tr>
                    <td> <input class="form-check-input" type="checkbox" value="${encodeURIComponent(JSON.stringify(aData[i]))}" name="act[]"></td>
                    <td>${aData[i].act_sid}</td>
                    <td>${aData[i].act_name}</td>
                    <td>${aData[i].group_date}</td>

                    <td rel="aduQty"><input type="number" min="0" class="aProdQty" value="${aData[i].adultQty}" ></td>

                    <td>${aData[i].price_adult}</td>

                    <td rel="kidQty"><input type="number" min="0" class="aProdQty" value="${aData[i].childQty}" ></td>

                    <td>${aData[i].price_kid}</td>
                    <td totalStock="${aData[i].ppl_max}" proSid="${aData[i].act_sid}" proDet="${aData[i].group_sid}" class="text-secondary">${astock}</td>

                    <td class="text-end" proSid="${aData[i].act_sid}" proDet="${aData[i].group_sid}" mem="${obj.sid}" onclick="deleteCartItem('${obj.sid}','${aData[i].act_sid}','${aData[i].group_sid}',this)"><i class="fa-solid fa-trash-can text-body-tertiary"></i></td>
                </tr>`;
        }

        oat.innerHTML = `
            <table class="ocd table table-border table-striped">
                <thead class="bg-success-subtle">
                    <tr>
                        <th scope="col"><input class="form-check-input" type="checkbox" name="actAll" onchange="selectAllActs()"></th>
                        <th scope="col">活動編號</th>
                        <th scope="col">活動名稱</th>
                        <th scope="col">期別</th>
                        <th scope="col">人數(成人)</th>
                        <th scope="col">單價(成人)</th>
                        <th scope="col">人數(小孩)</th>
                        <th scope="col">單價(小孩)</th>
                        <th scope="col">剩餘名額</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                ${actContent}
                </tbody>
            </table>`;
        oCartDisplay.append(oat);

        const aProdQties = document.querySelectorAll('.aProdQty');
        // console.log(aProdQties);
        for (let i of aProdQties) {
            i.addEventListener('change', aChangeStock);
            // i.addEventListener('change', calculateTotalPrice);
            // i.addEventListener('change', totalAmount);
        }

        //算活動總金額
        const aCheckboxes = document.querySelectorAll('input[name="act[]"]');
        for (let i of aCheckboxes) {
            i.addEventListener('change', (event) => {
                calAllTotalPrice();
            });
        }
    }
    //====算活動被選取的總額
    function calActTotalPrice() {
        const checkboxes = document.querySelectorAll('input[name="act[]"]:checked');
        let totalPrice = 0;

        checkboxes.forEach((checkbox) => {
            const decodedValue = decodeURIComponent(checkbox.value);
            const product = JSON.parse(decodedValue);
            const price_adult = parseFloat(product.price_adult);
            const price_kid = parseFloat(product.price_kid);
            const adultQty = parseInt(product.adultQty);
            const childQty = parseInt(product.childQty);
            const productTotal = price_adult * adultQty + price_kid * childQty;
            totalPrice += productTotal;
        });
        console.log('act total : ' + totalPrice); // 输出价格总和，你可以根据需要进行进一步处理或显示
        return totalPrice;
    }
    //====顯示coupon====
    function showCoupon(obj) {
        let oct = document.createElement("div");
        let cData = obj.coupons;
        let couContent = "";
        for (let i = 0; i < cData.length; i++) {
            couContent +=
                `<tr>
                    <td> <input class="form-check-input" type="radio" value="${cData[i].couponSend_sid}" name="coupon" coupon_price ="${cData[i].coupon_price}"></td>
                    <td>${cData[i].coupon_code}</td>
                    <td>${cData[i].coupon_name}</td>
                    <td>$${cData[i].coupon_price}</td>
                    <td>${(cData[i].coupon_expDate).slice(0,10)}</td>
                </tr>`;
        }
        oct.innerHTML =
            `<table class="ocd table table-border table-striped">
                        <thead class="bg-primary-subtle">
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
        const cCheckboxes = document.querySelectorAll('input[name="coupon"]');
        for (let i of cCheckboxes) {
            i.addEventListener('change', (event) => {
                calAllTotalPrice();
            });
        }
    }
    //====算活動被選取的總額
    function calCouponPrice() {
        const checkboxes = document.querySelector('input[name="coupon"]:checked');
        if (checkboxes && checkboxes.getAttribute('coupon_price')) {
            console.log('coupon_price : ' + checkboxes.getAttribute('coupon_price'));
            return parseInt(checkboxes.getAttribute('coupon_price'));
        } else {
            console.log('coupon_price : ' + 0);
            return 0;
        }
    }
    //====顯示地址及付款方式及送出BTN====
    function showPostnPay(obj) {
        oPostPayDisplay.innerHTML = ""
        const opp = document.createElement('div');
        // opp.classList.add('ocd');
        opp.classList.add('px-3');
        opp.innerHTML =
            `<div class="postInfo row g-0 pt-3">
                <h5 class="fw-bold mb-3">寄送資訊:</h5>
                <div class="mb-3 col-5 px-0 me-3">
                    <label for="postName" class="form-label">收件人姓名</label>
                    <input type="text" class="form-control" id="postName" name="postName" value="${obj.name}">
                    <div class="form-text text-danger"></div>
                </div>
                <div class="mb-3 col-5 me-3">
                    <label for="postMob" class="form-label">手機號碼</label>
                    <input type="text" class="form-control" id="postMob" name="postMobile" value="${obj.mobile}">
                    <div class="form-text text-danger"></div>
                </div>
                <div class="mb-3 col-10">
                    <label for="address" class="form-label">寄送地址</label>
                    <input type="text" class="form-control" id="address" name="address" value="">
                    <div class="form-text text-danger"></div>
                </div>
                <input type="text" name="member_sid" class="o-d-none" value="${obj.sid}">
            </div>`;

        oPostPayDisplay.append(opp);
        const oGetItemsForm = document.getElementById('oGetItemsForm');
        send.innerHTML = `        
        <div class="row pt-3 g-0">
            <div class="col-10">
                <div class="alert alert-danger text-center o-d-none" role="alert" id="oInfoBar"></div>
                <div class="d-flex justify-content-start px-3">
                <button type="button" class="btn btn-warning ms-auto" data-bs-target="#oMsgToClient">成立訂單</button>
                </div>
            </div>
        </div>    


        <!-- Modal Msg for client -->
        <div class="modal fade" id="oMsgToClient" tabindex="-1" aria-labelledby="oCMsgLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="oCMsgLabel">溫馨提醒會員</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        訂單成立後，匯款資訊將寄至會員信箱。<br>
                        請提醒會員於24小時內完成匯款。
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">退回編輯</button>
                        <button type="submit" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#oConfirmNewOrder">送出訂單</button>
                    </div>
                </div>
            </div>
        </div>`;
        oGetItemsForm.append(send);

    }
    //====選擇所有商品====
    function selectAllProducts() {
        const shopAllCheckbox = document.querySelector('input[name="shopAll"]');
        const prodCheckboxes = document.querySelectorAll('input[name="prod[]"]');
        if (shopAllCheckbox.checked) {
            prodCheckboxes.forEach((checkbox) => {
                checkbox.checked = true;
            });
        } else {
            prodCheckboxes.forEach((checkbox) => {
                checkbox.checked = false;
            });
        }
        calAllTotalPrice();
    }
    //====選擇所有活動====
    function selectAllActs() {
        const actAllCheckbox = document.querySelector('input[name="actAll"]');
        const actCheckboxes = document.querySelectorAll('input[name="act[]"]');

        if (actAllCheckbox.checked) {
            actCheckboxes.forEach((checkbox) => {
                checkbox.checked = true;
            });
        } else {
            actCheckboxes.forEach((checkbox) => {
                checkbox.checked = false;
            });
        }
        calAllTotalPrice();
    }
    //====前往訂單明細頁====
    function toOrderDetailsPage() {
        window.location.href = "o_02_orderDetails.php";
    }
    //====更新商品庫存,Cart Qty====
    function sChangeStock(e) {
        console.log('change');
        let tdself = e.target; //本身
        let qtyParent = tdself.closest('tr');
        let lastTd = qtyParent.querySelector('td:last-child');
        let sQty = lastTd.previousElementSibling;
        let stock = parseInt(sQty.getAttribute('totalStock'));
        let proSid = sQty.getAttribute('proSid');
        let proDet = sQty.getAttribute('proDet');
        let updatedQty = parseInt(e.target.value);

        fetch(`o_api01_3_updateQty.php?rel_sid=${proSid}&rel_seqNum_sid=${proDet}&prodQty=${updatedQty}`)
            .then(r => r.json())
            .then(obj => {
                if (obj.updateSuccess) {
                    sQty.innerHTML = stock - updatedQty; //更改頁面庫存量
                }
            })
            .catch(ex => console.log(ex))
        let checkboxVal = qtyParent.querySelector('td:first-child');
        let encodedString = checkboxVal.childNodes[0];
        const decodedString = JSON.parse(decodeURIComponent(encodedString.value));
        decodedString.prodQty = updatedQty;
        const updatedString = encodeURIComponent(JSON.stringify(decodedString));
        encodedString.value = updatedString;
        calAllTotalPrice();
    }
    //====更新活動庫存,Cart Qty===
    function aChangeStock(e) {
        console.log('change');
        let tdself = e.target; //本身
        let qtyParent = tdself.closest('tr'); //<tr>
        let lastTd = qtyParent.querySelector('td:last-child');
        let aQty = lastTd.previousElementSibling; //顯示庫存的td
        let stock = parseInt(aQty.getAttribute('totalStock')); //總庫存量
        let proSid = aQty.getAttribute('proSid');
        let proDet = aQty.getAttribute('proDet');
        let aduQty = qtyParent.querySelector('td[rel="aduQty"]>input').value;
        let kidQty = qtyParent.querySelector('td[rel="kidQty"]>input').value;
        let updatedStock = Math.floor(stock - (parseInt(aduQty) + parseInt(kidQty) / 2));

        aQty.innerHTML = updatedStock; //更改頁面庫存量
        fetch(`o_api01_3_updateQty.php?rel_sid=${proSid}&rel_seqNum_sid=${proDet}&adultQty=${aduQty}&childQty=${kidQty}`)
            .then(r => r.json())
            .then(obj => console.log(obj))
            .catch(ex => console.log(ex))

        let checkboxVal = qtyParent.querySelector('td:first-child');
        let encodedString = checkboxVal.querySelector('input:first-child');
        const decodedString = JSON.parse(decodeURIComponent(encodedString.value));
        let adOrkid = e.target.parentElement.getAttribute('rel');
        if (adOrkid === 'kidQty') {
            decodedString.childQty = kidQty;
        } else {
            decodedString.adultQty = aduQty;
        }
        const updatedString = encodeURIComponent(JSON.stringify(decodedString));
        encodedString.value = updatedString;
        calAllTotalPrice();
    }
    //====刪除購物車內容====
    function deleteCartItem(mem, pro, prod, x) {
        console.log('change');
        let qtyParent = x.closest('tr'); //<tr>
        console.log(qtyParent);
        let member_sid = x.getAttribute("mem");
        let pro_sid = x.getAttribute('proSid');
        let proDet_sid = x.getAttribute('proDet');
        fetch(`o_api01_4_deleteCartItem.php?rel_sid=${pro_sid}&rel_seqNum_sid=${proDet_sid}&member_sid=${member_sid}`)
            .then(r => r.json())
            .then(obj => {
                console.log(obj);
                console.log(obj.deleteSuccess);
                if (obj.deleteSuccess) {
                    qtyParent.style.display = "none";
                    alert("刪除成功");
                }
            })
            .catch(ex => console.log(ex))
    }
    //====更新數量時, 更新總金額====
    function totalAmount() {
        let allSubTotal = [];
        const sProdQties = document.querySelectorAll('.sProdQty');
        for (let s of sProdQties) {
            let sprice = parseInt(s.parentElement.nextElementSibling.innerHTML);
            let sqty = parseInt(s.value);
            allSubTotal.push(sprice * sqty);
        }
        const aProdQties = document.querySelectorAll('.aProdQty');
        for (let a of aProdQties) {
            let aprice = parseInt(a.parentElement.nextElementSibling.innerHTML);
            let aqty = parseInt(a.value);
            allSubTotal.push(aprice * aqty);
        }

        const totalPrice = allSubTotal.reduce((accumulator, currentValue) => accumulator + currentValue, 0);
        console.log(totalPrice);
    }
</script>
<?php include './partsNOEDIT/html-foot.php' ?>