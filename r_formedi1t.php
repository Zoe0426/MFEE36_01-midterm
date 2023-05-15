<?php
require './partsNOEDIT/connect-db.php';

// 餐廳類別
$sql = "SELECT `catg_sid`, `catg_name` FROM `rest_catg`";
$items = $pdo->query($sql)->fetchAll();

// 服務項目
$ssql = "SELECT `s_sid`, `s_name` FROM `rest_svc`";
$sitems = $pdo->query($ssql)->fetchAll();

// 攜帶規則
$rsql = "SELECT `r_sid`, `r_name` FROM `rest_rule`";
$ritems = $pdo->query($rsql)->fetchAll();



$sid = isset($_GET['rest_sid']) ? intval($_GET['rest_sid']) : 0;
// $sqll = "SELECT * FROM `rest_info` WHERE rest_sid = {$sid}";

$sqll = "SELECT ri.*, rc.`catg_name` FROM `rest_info` ri JOIN `rest_catg` rc ON ri.`catg_sid` = rc.`catg_sid` WHERE rest_sid = {$sid}";



$r = $pdo->query($sqll)->fetch();
if (empty($r)) {
    header('Location: r_read.php');
    exit;
}

$selectedValues = $r['weekly']; // 假設 $r['weekly'] 是 '0,2,4'
$selectedArray = explode(',', $selectedValues); // 將字串拆分成陣列 ['0', '2', '4']

$sql2 = "SELECT * FROM `rest_c_rs` WHERE rest_sid = {$sid}";
$a = $pdo->query($sql2)->fetchAll(PDO::FETCH_COLUMN, 1);

$sql3 = "SELECT * FROM `rest_c_rr` WHERE rest_sid = {$sid}";
$b = $pdo->query($sql3)->fetchAll(PDO::FETCH_COLUMN, 1);



?>
<?php include './partsNOEDIT/html-head.php' ?>
<style>

</style>
<?php include './partsNOEDIT/navbar.php' ?>

<!-- 這個需要隱藏，這是上傳圖片用的form -->
<!-- <form name="rest_pic" id="rest_pic">
    <input type="file" name="tempImg" accept="image/jpeg" id="tempImg">
</form> -->

<!-- 填表單的區域 -->

<form name="rest_form" class="px-3 pt-2 " onsubmit="checkForm(event)">
    <input type="hidden" name="sid" value="<?= $r['rest_sid'] ?>">
    <!-- 分頁 -->
    <div class="px-3 pt-4">


        <h3 class="mb-4 px-3">基本資料</h3>
        <!-- 圖片區 -->
        <div class="row mb-4 px-3">
            <!-- <div class="col-3" onclick="restImg()" id="finalImg">
                <img src="" alt="" id="imginfo">

            </div>
            <input type="text" name="pro_img" id="pro_img">
        </div> -->

            <!-- 資料區 -->
            <div class="row mb-4">
                <div class="col-6">
                    <label for="rest_name" class="form-label">餐廳名稱</label>
                    <input type="text" class="form-control" id="rest_name" name="rest_name" data-required="1" value="<?= $r['rest_name'] ?>">
                    <div class="form-text"></div>
                </div>
                <div class="col-3">
                    <label for="" class="form-label">餐廳類別</label>
                    <select class="form-select" name="catg_sid">
                        <option value="<?= $r['catg_sid'] ?>"><?= $r['catg_name'] ?></option>
                        <?php foreach ($items as $i) : ?>
                            <option value="<?= $i['catg_sid'] ?>"><?= $i['catg_name'] ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
                <!-- <div class="col-3">
                <label for="rest_menu" class="form-label">菜單上傳</label>
                <div class="input-group mb-3">
                    <input type="file" class="form-control" id="inputGroupFile01" name="rest_menu">
                </div>
            </div> -->
            </div>

            <div class="row mb-4">
                <div class="col-6">
                    <label for="rest_phone" class="form-label">餐廳電話</label>
                    <input type="text" class="form-control" id="rest_phone" name="rest_phone" data-required="1" value="<?= $r['rest_phone'] ?>">
                    <div class="form-text"></div>
                </div>

                <div class="col-6">
                    <label for="rest_address" class="form-label">餐廳地址</label>
                    <input type="text" class="form-control" id="rest_address" name="rest_address" data-required="1" value="<?= $r['rest_address'] ?>">
                    <div class="form-text"></div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-6">
                    <label for="rest_info" class="form-label">餐廳簡介</label>
                    <textarea class="form-control" id="rest_info" name="rest_info" placeholder="最多150字" data-required="1"><?= $r['rest_info'] ?></textarea>
                    <div id="rest_info" class="form-text"></div>
                </div>

                <div class="col-6">
                    <label for="rest_notice" class="form-label">注意事項</label>
                    <textarea class="form-control" id="rest_notice" name="rest_notice" placeholder="最多150字"><?= $r['rest_notice'] ?></textarea>
                    <div id="rest_notice" class="form-text"></div>
                </div>
            </div>
        </div>

        <hr>
        <!-- 餐廳特色 -->
        <div class="row px-3">
            <h3 class="mb-4">餐廳特色</h3>
            <!-- <div class="col-4">
            <label for="f_pic" class="form-label">特色圖片</label>
            <div onclick="restImg()" id="finalImg">
                <img src="" alt="" id="imginfo">

            </div>
            <input type="text" name="pro_img" id="pro_img">
        </div> -->
            <div class="col-8">
                <div class="col ">
                    <label for="rest_f_title" class="form-label">特色標題</label>
                    <input type="text" class="form-control" id="rest_f_title" name="rest_f_title" data-required="1" value="<?= $r['rest_f_title'] ?>">
                    <div class="form-text"></div>
                </div>
                <div class="col mt-4">
                    <label for="rest_f_ctnt" class="form-label">特色內容</label>
                    <textarea class="form-control" id="rest_f_ctnt" name="rest_f_ctnt" placeholder="最多150字"><?= $r['rest_f_ctnt'] ?></textarea>
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
                    <label for="date_start" class="form-label">開始日期</label>
                    <input type="date" class="form-control" id="date_start" name="date_start" data-required="1" value="<?= $r['date_start'] ?>">
                    <div class="form-text"></div>
                </div>
                <div class="col-3">
                    <label for="date_end" class="form-label">結束日期</label>
                    <input type="date" class="form-control" id="date_end" name="date_end" data-required="1" value="<?= $r['date_end'] ?>">
                    <div class="form-text"></div>
                </div>
                <div class="col-3">
                    <label for="p_max" class="form-label">人數上限</label>
                    <input type="text" class="form-control" id="p_max" name="p_max" data-required="1" value="<?= $r['p_max'] ?>">
                    <div class="form-text"></div>
                </div>
                <div class="col-3">
                    <label for="pt_max" class="form-label">寵物上限</label>
                    <input type="text" class="form-control" id="pt_max" name="pt_max" data-required="1" value="<?= $r['pt_max'] ?>">
                    <div class="form-text"></div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-3">
                    <label for="m_start" class="form-label">早上開始時間</label>
                    <input type="time" class="form-control" id="m_start" name="m_start" data-required="1" value="<?= $r['m_start'] ?>">
                    <div class="form-text"></div>
                </div>
                <div class="col-3">
                    <label for="m_end" class="form-label">早上結束時間</label>
                    <input type="time" class="form-control" id="m_end" name="m_end" data-required="1" value="<?= $r['m_end'] ?>">
                    <div class="form-text"></div>
                </div>
                <div class="col-3">
                    <label for="e_start" class="form-label">下午開始時間</label>
                    <input type="time" class="form-control" id="e_start" name="e_start" data-required="1" value="<?= $r['e_start'] ?>">
                    <div class="form-text"></div>
                </div>
                <div class="col-3">
                    <label for="e_end" class="form-label">下午結束時間</label>
                    <input type="time" class="form-control" id="e_end" name="e_end" data-required="1" value="<?= $r['e_end'] ?>">
                    <div class="form-text"></div>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-3">
                    <label for="n_start" class="form-label">晚上開始時間</label>
                    <input type="time" class="form-control" id="n_start" name="n_start" data-required="1" value="<?= $r['n_start'] ?>">
                    <div class="form-text"></div>
                </div>
                <div class="col-3">
                    <label for="n_end" class="form-label">晚上結束時間</label>
                    <input type="time" class="form-control" id="n_end" name="n_end" data-required="1" value="<?= $r['n_end'] ?>">
                    <div class="form-text"></div>
                </div>
                <!-- 用餐時間 -->
                <div class="col-6 ">
                    <label for="" class="form-label">用餐時間</label>
                    <div class="d-flex">
                        <div class=" form-check me-5">
                            <input class="form-check-input" type="radio" name="mltime" id="60min" value="60" <?php if ($r['ml_time'] == '60') echo 'checked'; ?>>
                            <label class="form-check-label" for="60min">
                                60分鐘
                            </label>
                        </div>
                        <div class="form-check me-5">
                            <input class="form-check-input" type="radio" name="mltime" id="90min" value="90" <?php if ($r['ml_time'] == '90') echo 'checked'; ?>>
                            <label class="form-check-label" for="90min">
                                90分鐘
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="mltime" id="120min" value="120" <?php if ($r['ml_time'] == '120') echo 'checked'; ?>>
                            <label class="form-check-label" for="120min">
                                120分鐘
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <!-- 星期幾 -->
            <!-- <div class="row mt-4">
                <label for="" class="form-label">星期幾</label>
                <div class="d-flex">
                    <div class="form-check me-5">
                        <input class="form-check-input" type="checkbox" value="0" id="sunday" name="weekly[]" <?php if (isset($r['weekly']) && in_array('0', $r['weekly'])) echo 'checked'; ?>>
                        <label class="form-check-label" for="sunday">
                            星期日
                        </label>
                    </div>
                    <div class="form-check me-5">
                        <input class="form-check-input" type="checkbox" value="1" id="monday" name="weekly[]" <?php if (isset($r['weekly']) && in_array('1', $r['weekly'])) echo 'checked'; ?>>
                        <label class="form-check-label" for="monday">
                            星期一
                        </label>
                    </div>
                    <div class="form-check me-5">
                        <input class="form-check-input" type="checkbox" value="2" id="tuesday" name="weekly[]" <?php if (isset($r['weekly']) && in_array('2', $r['weekly'])) echo 'checked'; ?>>
                        <label class="form-check-label" for="tuesday">
                            星期二
                        </label>
                    </div>
                    <div class="form-check me-5">
                        <input class="form-check-input" type="checkbox" value="3" id="wendsday" name="weekly[]" <?php if (isset($r['weekly']) && in_array('3', $r['weekly'])) echo 'checked'; ?>>
                        <label class="form-check-label" for="wendsday">
                            星期三
                        </label>
                    </div>
                    <div class="form-check me-5">
                        <input class="form-check-input" type="checkbox" value="4" id="thursday" name="weekly[]" <?php if (isset($r['weekly']) && in_array('4', $r['weekly'])) echo 'checked'; ?>>
                        <label class="form-check-label" for="thursday">
                            星期四
                        </label>
                    </div>
                    <div class="form-check me-5">
                        <input class="form-check-input" type="checkbox" value="5" id="friday" name="weekly[]" <?php if (isset($r['weekly']) && in_array('5', $r['weekly'])) echo 'checked'; ?>>
                        <label class="form-check-label" for="friday">
                            星期五
                        </label>
                    </div>
                    <div class="form-check ">
                        <input class="form-check-input" type="checkbox" value="6" id="saturday" name="weekly[]" <?php if (isset($r['weekly']) && in_array('6', $r['weekly'])) echo 'checked'; ?>>
                        <label class="form-check-label" for="saturday">
                            星期六
                        </label>
                    </div>
                </div>
            </div>
        </div> -->
            <!-- <hr> -->

            <!-- 服務/規範 -->

            <div class="mt-5 pt-2 px-3 mb-4">
                <label for="" class="form-label">
                    <h3>服務項目</h3>
                </label>
                <div class="d-flex">
                    <?php foreach ($sitems as $k => $j) : ?>
                        <div class="form-check me-5">
                            <input class="form-check-input" type="checkbox" value="<?= $j['s_sid'] ?>" name="rest_svc[]" id="rest_svc[]<?= $j['s_sid'] ?>" <?php if ($a && in_array($j['s_sid'], $a)) echo "checked"; ?>>
                            <label class="form-check-label" for="rest_svc[]<?= $j['s_sid'] ?>">
                                <?= $j['s_name'] ?>
                            </label>
                        </div>
                    <?php endforeach ?>
                </div>

            </div>

            <div class="mb-3 mt-5 pt-2 px-3">
                <label for="" class="form-label">
                    <h3>攜帶規則</h3>
                </label>
                <div class="d-flex ">
                    <?php foreach ($ritems as $k => $d) : ?>
                        <div class="form-check me-5">
                            <input class="form-check-input" type="checkbox" value="<?= $d['r_sid'] ?>" name="rest_rule[]" id="rest_rule[]<?= $d['r_sid'] ?>" <?php if ($b && in_array($d['r_sid'], $b)) echo "checked"; ?>>
                            <label class="form-check-label" for="rest_rule[]<?= $d['r_sid'] ?>">
                                <?= $d['r_name'] ?>
                            </label>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>

            <div class="row">
                <div class="alert alert-danger" role="alert" id="infoBar" style="display:none"></div>
                <button type="submit" class="col-3 btn btn-primary mt-4 mb-4">更新資訊</button>
            </div>

</form>
<?php include './partsNOEDIT/script.php' ?>
<script>
    function checkForm(event) {
        event.preventDefault();


        const fields = document.querySelectorAll('form *[data-required="1"]');
        const infoBar = document.querySelector('#infoBar');
        let isPass = true;

        // for (let f of fields) {
        //     f.style.border = '1px solid #ccc';
        //     f.nextElementSibling.innerHTML = '';
        // }


        // nameField.style.border = '1px solid #ccc';
        // nameField.nextElementSibling.innerHTML = '';

        // for (let f of fields) {
        //     if (!f.value) {
        //         isPass = false;
        //         f.style.border = '1px solid red';
        //         f.nextElementSibling.innerHTML = '請輸入資料';
        //     }
        // }

        // if (nameField.value.length < 2 || !nameField.value) {
        //     isPass = false;
        //     nameField.style.border = '1px solid red';
        //     nameField.nextElementSibling.innerHTML = '請輸入至少三個字';
        // }

        if (isPass) {
            const fd = new FormData(document.rest_form); //沒有外觀只有資料
            // const usp = new URLSearchParams(fd); //裡面一定要是FormData
            // console.log(usp.toString());


            fetch('r_update_api.php', {
                    method: 'POST',
                    body: fd,
                }).then(r => r.json())
                .then(obj => {
                    if (obj.success) {
                        infoBar.classList.remove('alert-danger');
                        infoBar.classList.add('alert-success');
                        infoBar.innerHTML = "編輯成功";
                        infoBar.style.display = 'block';

                    } else {
                        infoBar.classList.remove('alert-success');
                        infoBar.classList.add('alert-danger');
                        infoBar.innerHTML = "編輯失敗";
                        infoBar.style.display = 'block';
                    }
                    setTimeout(() => {
                        infoBar.style.display = 'none';
                    }, 2000);

                    console.log(obj);
                })
                .catch(ex => {
                    console.log(ex);
                    infoBar.classList.remove('alert-success');
                    infoBar.classList.add('alert-danger');
                    infoBar.innerHTML = "發生錯誤";
                    setTimeout(() => {
                        infoBar.style.display = 'none';
                    }, 2000);
                })
        }

    }
</script>
<?php include './partsNOEDIT/html-foot.php' ?>