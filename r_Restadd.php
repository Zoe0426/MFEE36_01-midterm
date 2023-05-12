<?php
require './partsNOEDIT/connect-db.php';
$sql = "SELECT `catg_sid`, `catg_name` FROM `rest_catg` WHERE 1";
$items = $pdo->query($sql)->fetchAll();
?>



<?php include './parts/html-head.php' ?>
<?php include './parts/navbar.php' ?>


<form name="rest_form" class="ps-2 pe-2">
    <!-- 分頁 -->
    <ul class="nav nav-pills mb-4 ">
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
    <div class="row mb-4">
        <div class="col-3">
            <div class="card">
                <img src="..." class="card-img-top" alt="...">
                <div class="card-body">
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                </div>
            </div>
        </div>
        <div class="col-3">
            <div class="card">
                <img src="..." class="card-img-top" alt="...">
                <div class="card-body">
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                </div>
            </div>
        </div>
        <div class="col-3">
            <div class="card">
                <img src="..." class="card-img-top" alt="...">
                <div class="card-body">
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                </div>
            </div>
        </div>
        <div class="col-3">
            <div class="card">
                <img src="..." class="card-img-top" alt="...">
                <div class="card-body">
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                </div>
            </div>
        </div>
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

    <!-- 餐廳特色 -->
    <h3 class="pt-3 mb-4">餐廳特色</h3>
    <div class="row mb-4">
        <div class="col-3">
            <label for="rest_fimg" class="form-label">特色圖片</label>
            <div class="card">
                <img src="..." class="card-img-top" alt="...">
                <div class="card-body">
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                </div>
            </div>
        </div>

        <div class="col-9">
            <label for="rest_ftitle" class="form-label">特色標題</label>
            <input type="text" class="form-control" id="rest_ftitle" name="rest_ftitle" data-required="1">
            <div class="form-text"></div>
        </div>
    </div>

</form>

<?php include './parts/script.php' ?>
<script>










</script>
<?php include './parts/html-foot.php' ?>