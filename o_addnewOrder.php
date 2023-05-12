<?php
require './partsNOEDIT/connect-db.php' ?>
<?php include './partsNOEDIT/html-head.php' ?>
<?php include './partsNOEDIT/navbar.php' ?>
<div class="container pt-4">

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

                    <input type="text" class="form-control mx-2 " id="sbname" name="sbname">
                    <input type="text" class="form-control mx-2 d-none " id="sbmobile" name="sbmobile">
                    <input type="text" class="form-control mx-2 d-none " id="sbmemsid" name="sbmemsid">

                </div>
                <div class="col-2">
                    <button type="submit" class="btn btn-warning ">搜尋</button>
                </div>

                <!-- <div class="col-4">
                    
                </div> -->
            </div>
            <div class="row">
                <div class="col-12">
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
                                <th scope="row" id="displaySid"></th>
                                <td id="displayName"></td>
                                <td id="displayPhone"></td>
                                <td id="displayBirth"></td>
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
    function getMemCart(e) {
        e.preventDefault();
        const fd = new FormData(document.getmem);
        fetch('o_api01_getMemCart.php', {
                method: 'POST',
                body: fd,
            }).then(r => r.json())
            .then(obj => {
                console.log(obj)
                document.getElementById("displaySid").innerHTML = obj.sid;
                document.getElementById("displayName").innerHTML = obj.name;
                document.getElementById("displayPhone").innerHTML = obj.mobile;
                document.getElementById("displayBirth").innerHTML = obj.birth;
            })
            .catch(ex => {
                console.log(ex);
            })
    }

    function searchm(e) {
        console.log("changed");
        const sbname = document.getElementById("sbname");
        const sbmobile = document.getElementById("sbmobile");
        const sbmemsid = document.getElementById("sbmemsid");
        let sb = parseInt(e.target.value);
        switch (sb) {
            case 1:
                sbname.style.display = "block";
                sbmobile.style.display = "none";
                sbmemsid.style.display = "none";
                break;
            case 2:
                sbname.style.display = "none";
                sbmobile.style.display = "block";
                sbmemsid.style.display = "none";
                break;
            case 3:
                sbname.style.display = "none";
                sbmobile.style.display = "none";
                sbmemsid.style.display = "block";
                break;

        }
    }
</script>
<?php include './partsNOEDIT/html-foot.php' ?>