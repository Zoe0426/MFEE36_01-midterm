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

    #getItemsForm input[type="number"] {
        width: 60px;
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
    <form id="getItemsForm" name="getItems" onsubmit="getItems(event)">
        <div class="container">
            <div class="row">
                <div class="col-11">
                    <!-- ===商品table -->
                    <table class="table table-border table-striped" id="prodTable">
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
                            <tr>
                                <td> <input class="form-check-input" type="checkbox" value="1" name="prod"></td>
                                <td id="oPsid">prod020</td>
                                <td id="oPname">catfood</td>
                                <td id="oPtype">pork</td>
                                <td><input type="number" id="oPqty" min="0" value="2"></td>
                                <td id="oPprice">300</td>
                                <td id="oPstock">40</td>
                            </tr>


                        </tbody>
                    </table>
                    <!-- ===活動table -->
                    <table class="table table-border table-striped" id="actTable">
                        <thead>
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
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td> <input class="form-check-input" type="checkbox" value="1" name="act"></td>
                                <td id="oAsid">20</td>
                                <td id="oAname">Sunday Park gather</td>
                                <td id="oAdate">2023-05-06</td>
                                <td><input type="number" min="0" id="oPaqty" value="2"></td>
                                <td id="oAaprice" value="200">200</td>
                                <td><input type="number" min="0" id="oPcqty" value="2"></td>
                                <td id="oAcprice" value="100">100</td>
                                <td id="oAstock">40</td>
                            </tr>

                        </tbody>
                    </table>
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
                    </table>`

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
</script>
<?php include './partsNOEDIT/html-foot.php' ?>