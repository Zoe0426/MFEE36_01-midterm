<?php
require './partsNOEDIT/connect-db.php' ?>
<?php include './partsNOEDIT/html-head.php' ?>
<style>
    .o-d-none {
        display: none;
    }

    .o-d-block {
        display: block;
    }

    #oGetItemsForm input[type="number"] {
        width: 60px;
    }

    .ocd:nth-child(odd) {
        border: 2px dashed #ffc107;
    }

    .ocd:nth-child(even) {
        border: 2px dashed #fff3cd;
    }
</style>
<?php include './partsNOEDIT/navbar.php' ?>
<div class="container pt-4">
    <!-- =====選擇會員===== -->
    <form name="getmem" onsubmit="getMemCart(event)">
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
                    <input type="text" class="form-control mx-2 " id="fakeinput" disabled>
                    <input type="text" class="form-control mx-2 o-d-none " id="sbname" name="sbname">
                    <input type="text" class="form-control mx-2 o-d-none " id="sbmobile" name="sbmobile">
                    <input type="text" class="form-control mx-2 o-d-none " id="sbmemsid" name="sbmemsid">
                </div>
                <div class="col-2">
                    <button type="submit" class="btn btn-warning ">搜尋</button>
                </div>
            </div>
            <div class="col-10 o-mem-table"> </div>
        </div>
    </form>
    <!-- =====顯示購物車內容===== -->
    <form id="oGetItemsForm" name="getItems" onsubmit="getItems(event)">
        <div class="container">
            <div class="row">
                <div id="oCartDisplay" class="col-10">

                </div>
            </div>
        </div>
    </form>


</div>
</div>
<?php include './partsNOEDIT/script.php' ?>
<script>
    // ====拿某會員資料及購物車內容====
    function getMemCart(e) {
        e.preventDefault();
        const fd = new FormData(document.getmem);
        fetch('o_api01_getMemCart.php', {
                method: 'POST',
                body: fd,
            }).then(r => r.json())
            .then(obj => {
                console.log(obj);
                showMemInfo(obj);
                showCartnCou(obj);
                // showAct(obj);
                // showCoupon(obj);
            })
            .catch(ex => {
                console.log(ex);
            })
    }
    // ====搜尋顯示哪種input====
    function searchm(e) {
        console.log("changed");
        const sbname = document.getElementById("sbname");
        const sbmobile = document.getElementById("sbmobile");
        const sbmemsid = document.getElementById("sbmemsid");
        const fakeinput = document.getElementById("fakeinput");
        let sb = e.target.value;
        fakeinput.style.display = "none";
        console.log("none")
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
                                <th scope="row" id="displaySid">${obj.sid}</th>
                                <td id="displayName">${obj.name}</td>
                                <td id="displayPhone">${obj.mobile}</td>
                                <td id="displayBirth">${obj.birth}</td>
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
        // console.log(sData);
        let shopContent = "";
        for (let i = 0; i < sData.length; i++) {
            shopContent +=
                `<tr>
                    <td><input class="form-check-input" type="checkbox" value="[${sData[i].pro_sid},${sData[i].proDet_sid},${sData[i].pro_name},${sData[i].proDet_name},${sData[i].proDet_price},]" name="prod"></td>
                    <td>${sData[i].pro_sid}-${sData[i].proDet_sid}</td>
                    <td>${sData[i].pro_name}</td>
                    <td>${sData[i].proDet_name}</td>
                    <td><input type="number" id="oMqty${i}" min="0" value="${sData[i].prodQty}"></td>
                    <td>${sData[i].proDet_price}</td>
                    <td id="oSqty${i}">${sData[i].proDet_qty}</td>
                </tr>`;
        }
        ost.innerHTML = `<table class="ocd table table-border table-striped " id="prodTable">
                <thead>
                    <tr>
                        <th scope="col"><input class="form-check-input" type="checkbox" name="shopAll" onchange="selectAllProducts()"></th>
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
        // console.log(ost);
        oCartDisplay.append(ost);

        //             <!-- ===活動table -->
        let oat = document.createElement("div");
        let aData = obj.actlist;
        console.log(aData);
        let actContent = "";
        //             <table class="ocd table table-border table-striped" id="actTable">
        //                 <thead>
        //                     <tr>
        //                         <th scope="col"><input class="form-check-input" type="checkbox" name="actAll" onchange="selectAllActs()"></th>
        //                         <th scope="col">活動編號</th>
        //                         <th scope="col">活動名稱</th>
        //                         <th scope="col">期別</th>
        //                         <th scope="col">人數(成人)</th>
        //                         <th scope="col">單價(成人)</th>
        //                         <th scope="col">人數(小孩)</th>
        //                         <th scope="col">單價(小孩)</th>
        //                         <th scope="col">剩餘名額</th>
        //                     </tr>
        //                 </thead>
        //                 <tbody>
        //                     <tr>
        //                         <td> <input class="form-check-input" type="checkbox" value="1" name="act"></td>
        //                         <td id="oAsid">20</td>
        //                         <td id="oAname">Sunday Park gather</td>
        //                         <td id="oAdate">2023-05-06</td>
        //                         <td><input type="number" min="0" id="oPaqty" value="2"></td>
        //                         <td id="oAaprice" value="200">200</td>
        //                         <td><input type="number" min="0" id="oPcqty" value="2"></td>
        //                         <td id="oAcprice" value="100">100</td>
        //                         <td id="oAstock">40</td>
        //                     </tr>

        //                 </tbody>
        //             </table>
        //             <!-- ===coupon -->
        //             <table class="ocd table table-border table-striped" id="couponTable">
        //                 <thead>
        //                     <tr>
        //                         <th scope="col"></th>
        //                         <th scope="col">優惠券編號</th>
        //                         <th scope="col">優惠券名稱</th>
        //                         <th scope="col">優惠金額</th>
        //                         <th scope="col">使用期限</th>
        //                     </tr>
        //                 </thead>
        //                 <tbody>
        //                     <tr>
        //                         <td> <input class="form-check-input" type="checkbox" value="1" name="coupon"></td>
        //                         <td id="oCcode">20</td>
        //                         <td id="oCname">Sunday Park gather</td>
        //                         <td id="oCprice">2023-05-06</td>
        //                         <td id="oCdate">2023-05-06</td>
        //                     </tr>
        //                 </tbody>
        //             </table>`

    }
</script>
<?php include './partsNOEDIT/html-foot.php' ?>