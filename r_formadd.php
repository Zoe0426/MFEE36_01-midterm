<?php
require './partsNOEDIT/connect-db.php';

// 餐廳類別
$sql = "SELECT `catg_sid`, `catg_name` FROM `rest_catg` WHERE 1";
$items = $pdo->query($sql)->fetchAll();

// 服務項目
$ssql = "SELECT `s_sid`, `s_name` FROM `rest_svc` WHERE 1";
$sitems = $pdo->query($ssql)->fetchAll();

//攜帶規則
$rsql = "SELECT `r_sid`, `r_name` FROM `rest_rule` WHERE 1";
$ritems = $pdo->query($rsql)->fetchAll();

?>
<?php include './partsNOEDIT/html-head.php' ?>
<style>
    #rest_pic,
    #pro_img {
        /* form1表單與回傳照片名稱隱藏*/
        display: none;
    }

    #finalImg {
        /* 設計自己要放的照片框框 */
        border: 1px dashed lightgray;
        border-radius: 4px;
        height: 360px;

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

<!-- 填表單的區域 -->

<form name="rest_form" class="px-3 pt-2 " onsubmit="checkForm(event)">
    <!-- 分頁 -->
    <div class="px-3 pt-4">


        <h3 class="mb-4">基本資料</h3>
        <!-- 圖片區 -->
        <div class="row mb-4 ">
            <div class="col-3" onclick="restImg()" id="finalImg">
                <img src="" alt="" id="imginfo">

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
                <textarea class="form-control" id="rest_info" name="rest_info" placeholder="最多150字" data-required="1"></textarea>
                <div id="rest_info" class="form-text"></div>
            </div>

            <div class="col-6">
                <label for="rest_notice" class="form-label">注意事項</label>
                <textarea class="form-control" id="rest_notice" name="rest_notice" placeholder="最多150字"></textarea>
                <div id="rest_notice" class="form-text"></div>
            </div>
        </div>
    </div>
    <hr>
    <!-- 餐廳特色 -->
    <div class="row px-3">
        <div class="col-4">
            <label for="f_pic" class="form-label">特色圖片</label>
            <div onclick="restImg()" id="finalImg">
                <img src="" alt="" id="imginfo">

            </div>
            <input type="text" name="pro_img" id="pro_img">
        </div>
        <div class="col-8">
            <div class="col mt-5 pt-4">
                <label for="f_title" class="form-label">特色標題</label>
                <input type="text" class="form-control" id="f_title" name="f_title" data-required="1">
                <div class="form-text"></div>
            </div>
            <div class="col mt-4">
                <label for="f_content" class="form-label">特色內容</label>
                <textarea class="form-control" id="f_content" name="f_content" placeholder="最多150字"></textarea>
                <div id="f_content" class="form-text"></div>
            </div>
        </div>
    </div>



    <hr>
    <!-- 營業設定 -->

    <div class="px-3 mb-4">
        <h3 class="mb-4">營業設定</h3>

        <!-- 資料區 -->
        <div class="row mb-4">
            <div class="col-3">
                <label for="start_date" class="form-label">開始日期</label>
                <input type="date" class="form-control" id="start_date" name="start_date" data-required="1">
                <div class="form-text"></div>
            </div>
            <div class="col-3">
                <label for="end_date" class="form-label">結束日期</label>
                <input type="date" class="form-control" id="end_date" name="end_date" data-required="1">
                <div class="form-text"></div>
            </div>
            <div class="col-3">
                <label for="people_max" class="form-label">人數上限</label>
                <input type="text" class="form-control" id="people_max" name="people_max" data-required="1">
                <div class="form-text"></div>
            </div>
            <div class="col-3">
                <label for="pet_max" class="form-label">寵物上限</label>
                <input type="text" class="form-control" id="pet_max" name="pet_max" data-required="1">
                <div class="form-text"></div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-3">
                <label for="e_start" class="form-label">早上開始時間</label>
                <input type="time" class="form-control" id="e_start" name="e_start" data-required="1">
                <div class="form-text"></div>
            </div>
            <div class="col-3">
                <label for="e_end" class="form-label">早上結束時間</label>
                <input type="time" class="form-control" id="e_end" name="e_end" data-required="1">
                <div class="form-text"></div>
            </div>
            <div class="col-3">
                <label for="a_start" class="form-label">下午開始時間</label>
                <input type="time" class="form-control" id="a_start" name="a_start" data-required="1">
                <div class="form-text"></div>
            </div>
            <div class="col-3">
                <label for="a_end" class="form-label">下午結束時間</label>
                <input type="time" class="form-control" id="a_end" name="a_end" data-required="1">
                <div class="form-text"></div>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-3">
                <label for="n_start" class="form-label">晚上開始時間</label>
                <input type="time" class="form-control" id="n_start" name="n_start" data-required="1">
                <div class="form-text"></div>
            </div>
            <div class="col-3">
                <label for="n_end" class="form-label">晚上結束時間</label>
                <input type="time" class="form-control" id="n_end" name="n_end" data-required="1">
                <div class="form-text"></div>
            </div>
            <!-- 用餐時間 -->
            <div class="col-6 ">
                <label for="" class="form-label">用餐時間</label>
                <div class="d-flex">
                    <div class=" form-check me-5">
                        <input class="form-check-input" type="radio" name="60min" id="60min" value="60">
                        <label class="form-check-label" for="60min">
                            60分鐘
                        </label>
                    </div>
                    <div class="form-check me-5">
                        <input class="form-check-input" type="radio" name="90min" id="90min" value="90">
                        <label class="form-check-label" for="90min">
                            90分鐘
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="120min" id="120min" value="120">
                        <label class="form-check-label" for="120min">
                            120分鐘
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <!-- 星期幾 -->
        <div class="row mt-4">
            <label for="" class="form-label">星期幾</label>
            <div class="d-flex">
                <div class="form-check me-5">
                    <input class="form-check-input" type="checkbox" value="0" id="sunday">
                    <label class="form-check-label" for="sunday">
                        星期日
                    </label>
                </div>
                <div class="form-check me-5">
                    <input class="form-check-input" type="checkbox" value="1" id="monday">
                    <label class="form-check-label" for="monday">
                        星期一
                    </label>
                </div>
                <div class="form-check me-5">
                    <input class="form-check-input" type="checkbox" value="2" id="tuesday">
                    <label class="form-check-label" for="tuesday">
                        星期二
                    </label>
                </div>
                <div class="form-check me-5">
                    <input class="form-check-input" type="checkbox" value="3" id="wendsday">
                    <label class="form-check-label" for="wendsday">
                        星期三
                    </label>
                </div>
                <div class="form-check me-5">
                    <input class="form-check-input" type="checkbox" value="4" id="thursday">
                    <label class="form-check-label" for="thursday">
                        星期四
                    </label>
                </div>
                <div class="form-check me-5">
                    <input class="form-check-input" type="checkbox" value="5" id="friday">
                    <label class="form-check-label" for="friday">
                        星期五
                    </label>
                </div>
                <div class="form-check ">
                    <input class="form-check-input" type="checkbox" value="6" id="saturday">
                    <label class="form-check-label" for="saturday">
                        星期六
                    </label>
                </div>
            </div>
        </div>
    </div>
    <hr>

    <!-- 服務/規範 -->

    <div class="mt-3 px-3 mb-4">
        <label for="" class="form-label">
            <h3>服務項目</h3>
        </label>
        <div class="d-flex ">
            <?php foreach ($sitems as $k => $j) : ?>
                <div class="form-check me-5">
                    <input class="form-check-input" type="checkbox" value="<?= $j['s_sid'] ?>" name="service" id="service<?= $j['s_sid'] ?>">
                    <label class="form-check-label" for="service<?= $j['s_sid'] ?>">
                        <?= $j['s_name'] ?>
                    </label>
                </div>
            <?php endforeach ?>
        </div>
    </div>

    <div class="mb-3 mt-3 px-3">
        <label for="" class="form-label">
            <h3>攜帶規則</h3>
        </label>
        <div class="d-flex ">
            <?php foreach ($ritems as $k => $r) : ?>
                <div class="form-check me-5">
                    <input class="form-check-input" type="checkbox" value="<?= $r['r_sid'] ?>" name="rule" id="rule<?= $r['r_sid'] ?>">
                    <label class="form-check-label" for="rule<?= $r['r_sid'] ?>">
                        <?= $r['r_name'] ?>
                    </label>
                </div>
            <?php endforeach ?>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">新增餐廳</button>


</form>
<?php include './partsNOEDIT/script.php' ?>
<script>
    function checkForm(event) {

        event.preventDefault();
        let isPass = true;

        // TODO: 驗證表格內容，若不通過，isPass ＝false； 

        if (isPass) { //格式完全正確，呼叫api
            const fd = new FormData(document.rest_form);
            fetch('r_add_api.php', {
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

    function restImg() {
        //模擬點擊
        tempImg.click();
    }

    tempImg.addEventListener("change", () => {
        const fd = new FormData(document.rest_pic);
        fetch('r_file_api.php', { //這邊請填入自己要連結的api名稱
                method: 'POST',
                body: fd,
            }).then(r => r.json())
            .then(obj => {
                if (obj.filename) {
                    //這邊怪怪的
                    const finalImg = document.querySelector('#finalImg');
                    const pro_img = document.querySelector('#pro_img');
                    finalImg.firstChild.src = `./imgs/${obj.filename}`;
                    finalImg.firstChild.style.display = "block";
                    pro_img.value = obj.filename;
                }
            }).catch(ex => {
                console.log(ex)
            })
    })
</script>
<?php include './partsNOEDIT/html-foot.php' ?>