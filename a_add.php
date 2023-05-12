<?php include './partsNOEDIT/html-head.php' ?>
<?php include './partsNOEDIT/navbar.php' ?>

<form name="form1" onsubmit="checkForm()">
    <div class="mb-3">
        <div class="mb-3">
            <label for="act_name" class="form-label">活動標題</label>
            <input type="text" class="form-control" id="act_name" name="act_name">
            <div class="form-text"></div>
        </div>
    </div>
    <div class="mb-3">
        <label for="type_sid" class="form-label">活動類型</label>
        <select class="form-select" aria-label="Default select example">
            <option selected>--請選擇--</option>
            <option value="1">主題派對</option>
            <option value="2">在地活動</option>
            <option value="3">市集展覽</option>
            <option value="4">毛孩講座</option>
            <option value="5">寵物學校</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="ppl_max" class="form-label">人數上限</label>
        <input type="text" class="form-control" id="ppl_max" name="ppl_max">
        <div class="form-text"></div>
    </div>
    <div class="mb-3">
        <label for="act_city" class="form-label">縣市</label>
        <input type="text" class="form-control" id="act_city" name="act_city">
        <div class="form-text"></div>
        <label for="act_area" class="form-label">地區</label>
        <input type="text" class="form-control" id="act_area" name="act_area">
        <div class="form-text"></div>
        <label for="act_address" class="form-label">地址</label>
        <input type="text" class="form-control" id="act_address" name="act_address">
        <div class="form-text"></div>
    </div>
    <div class="mb-3">
        <label for="group_date" class="form-label">梯次</label>
        <input type="date" class="form-control" id="group_date" name="group_date">
        <div class="form-text"></div>
    </div>
    <div class="mb-3">
        <label for="group_time" class="form-label">時段</label>
        <select class="form-select" aria-label="Default select example">
            <option selected>--請選擇--</option>
            <option value="0">上午</option>
            <option value="1">下午</option>
            <option value="2">全天</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="price_adult" class="form-label">價格(大人)</label>
        <input type="text" class="form-control" id="price_adult" name="price_adult">
        <div class="form-text"></div>
        <label for="price_kid" class="form-label">價格(小孩)</label>
        <input type="text" class="form-control" id="price_kid" name="price_kid">
        <div class="form-text"></div>
    </div>
    <div class="mb-3">
        <label for="formFile" class="form-label">照片上傳</label>
        <input class="form-control" type="file" id="formFile">
    </div>
    <div class="mb-3">
        <label for="act_content" class="form-label">活動內容</label>
        <textarea class="form-control" id="act_content" rows="3"></textarea>
    </div>
    <div class="mb-3">
        <label for="act_policy" class="form-label">活動規範</label>
        <textarea class="form-control" id="act_policy" rows="3"></textarea>
    </div>

    <button type="submit" class="btn btn-primary">送出</button>
</form>

<?php include './partsNOEDIT/script.php' ?>
<script>


</script>
<?php include './partsNOEDIT/html-foot.php' ?>