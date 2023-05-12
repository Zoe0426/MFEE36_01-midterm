<?php
require './partsNOEDIT/connect-db.php';

$sql = "SELECT `catg_sid`, `catg_name` FROM `rest_catg` WHERE 1";
$items = $pdo->query($sql)->fetchAll();

?>
<?php include './partsNOEDIT/html-head.php' ?>
<style>
    #rest_pic,
    #pro_img {
        /* 要記得將傳照片的form1表單與回傳照片名稱隱藏起來*/
        display: none;
    }

    #finalImg {
        /* 設計自己要放的照片框框 */
        border: 1px dashed lightgray;
        border-radius: 4px;
        height: 200px;
        position: relative;
    }


    #imginfo {
        /* 為了不讓div內的img超出，故要記得做下列設定 */
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: none;
        position: absolute;
    }
</style>
<?php include './partsNOEDIT/navbar.php' ?>

<!-- 這個需要隱藏，這是上傳圖片用的form -->
<form name="rest_pic" id="rest_pic">
    <input type="file" name="tempImg" accept="image/jpeg" id="tempImg">
</form>

<form name="rest_form" class="px-3 pt-2 " onsubmit="checkForm(event)">

    <!-- 分頁 -->
    <ul class=" nav nav-pills mb-4 mt-4">
        <li class="nav-item">
            <a class="nav-link active" href="#">基本資料</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">營業設定</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="checkbox.php">服務/規範</a>
        </li>

    </ul>


    <h3 class="mb-4">基本資料</h3>
    <!-- 圖片區 -->
    <div class="row mb-4 ">
        <div class="col-3" onclick="shopAddMainImg()" id="finalImg">
            <img src="" alt="" id="imginfo"><i class="fa-solid fa-image"></i>
        </div>
        <input type="text" name="pro_img" id="pro_img">

        <div class="col-3" onclick="shopAddMainImg()" id="finalImg">
            <img src="" alt="" id="imginfo"><i class="fa-solid fa-image"></i>
        </div>
        <input type="text" name="pro_img" id="pro_img">

        <div class="col-3" onclick="shopAddMainImg()" id="finalImg">
            <img src="" alt="" id="imginfo"><i class="fa-solid fa-image"></i>
        </div>
        <input type="text" name="pro_img" id="pro_img">

        <div class="col-3" onclick="shopAddMainImg()" id="finalImg">
            <img src="" alt="" id="imginfo"><i class="fa-solid fa-image "></i>
        </div>
        <input type="text" name="pro_img" id="pro_img">
    </div>

    <!-- 資料區 -->
    <div class="row mb-4">
        <div class="col-6">
            <label for="rest_name" class="form-label">餐廳名稱</label>
            <input type="text" class="form-control" id="rest_name" name="rest_name" data-required="1">
            <div class="form-text"></div>
        </div>
        <div class="col-3">
            <label for="" class="form-label">餐廳類別</label>
            <select class="form-select" name="catg">
                <option value="">--請選擇餐廳類別--</option>
                <?php foreach ($items as $i) : ?>
                    <option value="<?= $i['catg_sid'] ?>"><?= $i['catg_name'] ?></option>
                <?php endforeach ?>
            </select>
        </div>
        <div class="col-3">
            <label for="rest_menu" class="form-label">菜單上傳</label>
            <div class="input-group mb-3">
                <input type="file" class="form-control" id="inputGroupFile01">
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-6">
            <label for="rest_phone" class="form-label">餐廳電話</label>
            <input type="text" class="form-control" id="rest_phone" name="rest_phone" data-required="1">
            <div class="form-text"></div>
        </div>

        <div class="col-6">
            <label for="rest_address" class="form-label">餐廳地址</label>
            <input type="text" class="form-control" id="rest_address" name="rest_address" data-required="1">
            <div class="form-text"></div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-6">
            <label for="rest_info" class="form-label">餐廳簡介</label>
            <textarea class="form-control" id="rest_info" name="rest_info" data-required="1"></textarea>
            <div id="rest_info" class="form-text"></div>
        </div>

        <div class="col-6">
            <label for="rest_notice" class="form-label">注意事項</label>
            <textarea class="form-control" id="rest_notice" name="rest_notice"></textarea>
            <div id="rest_notice" class="form-text"></div>
        </div>
    </div>



</form>

<?php include './partsNOEDIT/script.php' ?>
<script>
    function checkForm(event) {

        event.preventDefault();
        let isPass = true; // 預設值是通過的

        // TODO: 驗證表格內容，若不通過，isPass ＝false； 

        if (isPass) { //格式完全正確，呼叫api

            fetch('r_C_add_to_multifiles copy.php', {
                    method: 'POST',
                    body: fd,
                })
                .then(r => r.json())
                .then(obj => {
                    console.log(obj);
                    //obj 會拿到 api 回傳的結果，請自由使用：）
                })
                .catch(ex => {
                    console.log(ex);
                })

        } else {
            // 沒通過檢查
        }
    }

    const tempImg = document.querySelector("#tempImg");

    function shopAddMainImg() {
        //模擬點擊
        tempImg.click();
    }

    tempImg.addEventListener("change", () => {
        const fd = new FormData(document.form1);
        fetch('r_file_apiTemp.php', { //這邊請填入自己要連結的api名稱
                method: 'POST',
                body: fd,
            }).then(r => r.json())
            .then(obj => {
                if (obj.filename) {
                    const finalImg = document.querySelector('#finalImg');
                    const pro_img = document.querySelector('#pro_img');
                    finalImg.firstChild.src = `./shopImgs/${obj.filename}`;
                    finalImg.firstChild.style.display = "block";
                    pro_img.value = obj.filename;
                }
            }).catch(ex => {
                console.log(ex)
            })
    })
</script>
<?php include './partsNOEDIT/html-foot.php' ?>