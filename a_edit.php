<?php
//require './partsNOEDIT/a_admin_required.php';
require './partsNOEDIT/connect-db.php';

$act_sid = isset($_GET['act_sid']) ? intval($_GET['act_sid']) : 0;
$sql = "SELECT `act_sid`, `type_sid`, `act_name`, `act_content`, `act_policy`, `act_city`, `act_area`, `act_address`, `act_pic_sid`, `act_pet_type`, `act_from`, `post_status` FROM `act_info` WHERE act_sid={$act_sid}";

$r = $pdo->query($sql)->fetch();
//echo print_r($r);
if (empty($r)) {
    header('Location: a_list_admin.php');
    exit;
}

$sql1 = "SELECT `type_sid`, `type_name` FROM `act_type`";
$typeList = $pdo->query($sql1)->fetchAll();

$sql2 = "SELECT DISTINCT `group_time` FROM `act_group`";
$groupTimeList = $pdo->query($sql2)->fetchAll();

$sql3 = "SELECT `group_sid`, `act_sid`, `group_date`, `group_time`, `price_adult`, `price_kid`, `group_status`, `ppl_max`, `act_post_date` FROM `act_group`";
$groupList = $pdo->query($sql3)->fetch();



?>
<?php include './partsNOEDIT/html-head.php' ?>
<style>
    .form-text {
        color: red;
    }
</style>
<?php include './partsNOEDIT/navbar.php' ?>
<style>
    .form-text {
        color: red;
    }
</style>


<div class="container">
    <div class="row">


        <div class="card col-6">
            <div class="card-body">
                <h2>新增活動</h2>
                <form name="form1" onsubmit="checkForm(event)">
                    <input type="hidden" name="act_sid" value="<?= $r['act_sid'] ?>">
                    <div class="mb-3">
                        <div class="mb-3">
                            <label for="act_name" class="form-label">活動標題</label>
                            <input type="text" class="form-control" id="act_name" name="act_name" data-required="1" value="<?= $r['act_name'] ?>">
                            <div class="form-text"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="type_sid" class="form-label">活動類型</label>
                        <select class="form-select" id="type_sid" name="type_sid" data-required="1">
                            <?php foreach ($typeList as $t) : ?>
                                <option value="<?= $t['type_sid'] ?>" <?php if ($t['type_sid'] == $r['type_sid']) echo "selected" ?>><?= $t['type_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-text"></div>
                    </div>
                    <div class="mb-3">
                        <label for="ppl_max" class="form-label">人數上限</label>
                        <input type="text" class="form-control" id="ppl_max" name="ppl_max" data-required="1" value="<?= $groupList['ppl_max'] ?>">
                        <div class="form-text"></div>
                    </div>
                    <div class="mb-3">
                        <label for="act_city" class="form-label">縣市</label>
                        <input type="text" class="form-control" id="act_city" name="act_city" data-required="1" value="<?= $r['act_city'] ?>">
                        <div class="form-text"></div>
                        <label for="act_area" class="form-label">地區</label>
                        <input type="text" class="form-control" id="act_area" name="act_area" data-required="1" value="<?= $r['act_area'] ?>">
                        <div class="form-text"></div>
                        <label for="act_address" class="form-label">地址</label>
                        <input type="text" class="form-control" id="act_address" name="act_address" data-required="1" value="<?= $r['act_address'] ?>">
                        <div class="form-text"></div>
                    </div>
                    <div class="mb-3">
                        <label for="group_date" class="form-label">梯次</label>
                        <input type="date" class="form-control" id="group_date" name="group_date" data-required="1" value="<?= $r['group_date'] ?? date('Y-m-d') ?>" min="<?= date('Y-m-d') ?>">
                        <div class="form-text"></div>
                    </div>
                    <div class="mb-3">
                        <label for="group_time" class="form-label">時段</label>
                        <select class="form-select" id="group_time" name="group_time" data-required="1">
                            <option value="">--請選擇--</option>
                            <?php foreach ($groupTimeList as $gt) : ?>
                                <?php $selected = ($gt['group_time'] == $group_time) ? 'selected' : ''; ?>
                                <option value="<?= $gt['group_time'] ?>" <?= $selected ?>>
                                    <?php if ($gt['group_time'] == 0) : ?>
                                        上午
                                    <?php elseif ($gt['group_time'] == 1) : ?>
                                        下午
                                    <?php elseif ($gt['group_time'] == 2) : ?>
                                        全天
                                    <?php endif; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-text"></div>
                    </div>
                    <div class="mb-3">
                        <label for="price_adult" class="form-label">價格(大人)</label>
                        <input type="text" class="form-control" id="price_adult" name="price_adult" data-required="1" value="<?= $groupList['price_adult'] ?>">
                        <div class="form-text"></div>
                        <label for="price_kid" class="form-label">價格(小孩)</label>
                        <input type="text" class="form-control" id="price_kid" name="price_kid" data-required="1" value="<?= $groupList['price_kid'] ?>">
                        <div class="form-text"></div>
                    </div>
                    <!-- <div class="mb-3">
        <label for="act_pic" class="form-label">照片上傳</label>
        <input class="form-control" type="file" id="act_pic" name="act_pic">
    </div> -->
                    <div class="mb-3">
                        <label for="act_content" class="form-label">活動內容</label>
                        <textarea class="form-control" id="act_content" name="act_content" data-required="1"><?= $r['act_content'] ?></textarea>
                        <div class="form-text"></div>
                    </div>
                    <div class="mb-3">
                        <label for="act_policy" class="form-label">活動規範</label>
                        <textarea class="form-control" id="act_policy" name="act_policy" data-required="1"><?= $r['act_policy'] ?></textarea>
                        <div class="form-text"></div>
                    </div>

                    <div class="alert alert-danger" role="alert" id="infoBar" style="display:none"></div>

                    <button type="submit" class="btn btn-primary">編輯</button>
                </form>
            </div>
        </div>
    </div>
</div>


<?php include './partsNOEDIT/script.php' ?>

<script>
    const nameField = document.querySelector('#act_name');
    const type = document.querySelector('#type_sid');
    const pplMax = document.querySelector('#ppl_max');
    const city = document.querySelector('#act_city');
    const area = document.querySelector('#act_area');
    const address = document.querySelector('#act_address');
    const date = document.querySelector('#group_date');
    const time = document.querySelector('#group_time');
    const adult = document.querySelector('#price_adult');
    const kid = document.querySelector('#price_kid');
    const content = document.querySelector('#act_content');
    const policy = document.querySelector('#act_policy');

    const infoBar = document.querySelector('#infoBar');
    // 取得必填欄位
    const fields = document.querySelectorAll('form *[data-required="1"]');


    //for測試：
    //console.log(date.value); //date裡面是空的 
    //console.log(fields);

    function checkForm(event) {
        event.preventDefault();

        for (let f of fields) {
            f.style.border = '1px solid #ccc';
            f.nextElementSibling.innerHTML = '';
        }


        let isPass = true; // 預設值是通過的

        //輸入的長度小於2時 跳出提示
        if (nameField.value.length < 2) {
            isPass = false;

            nameField.style.border = '1px solid red';
            nameField.nextElementSibling.innerHTML = '請輸入至少兩個字'

            pplMax.style.border = '1px solid red';
            pplMax.nextElementSibling.innerHTML = '請輸入人數'

            city.style.border = '1px solid red';
            city.nextElementSibling.innerHTML = '請輸入縣市名稱'

            area.style.border = '1px solid red';
            area.nextElementSibling.innerHTML = '請輸入地區名稱'

            address.style.border = '1px solid red';
            address.nextElementSibling.innerHTML = '請輸入地址'

            adult.style.border = '1px solid red';
            adult.nextElementSibling.innerHTML = '請輸入價格'

            kid.style.border = '1px solid red';
            kid.nextElementSibling.innerHTML = '請輸入價格'

            content.style.border = '1px solid red';
            content.nextElementSibling.innerHTML = '請輸入內容'

            policy.style.border = '1px solid red';
            policy.nextElementSibling.innerHTML = '請輸入規範'

        }

        //沒有選到時 跳出提示
        if (type.value === "--請選擇--") {
            type.style.border = '1px solid red';
            type.nextElementSibling.innerHTML = '請選擇'
        }

        //日期無法成功
        if (date.value === "Y-m-d") {
            date.style.border = '1px solid red';
            date.nextElementSibling.innerHTML = '請選擇'
        }

        if (time.value === "--請選擇--") {
            time.style.border = '1px solid red';
            time.nextElementSibling.innerHTML = '請選擇'
        }

        if (isPass) {
            const fd = new FormData(document.form1); // 沒有外觀的表單

            // const usp = new URLSearchParams(fd); // 可以轉換為 urlencoded 格式
            // console.log(usp.toString());
            // console.log(fd);


            //infobar的東西
            fetch('a_edit_api.php', {
                    method: 'POST',
                    body: fd, // Content-Type 省略, multipart/form-data
                }).then(r => r.json())
                .then(obj => {
                    console.log(obj);
                    if (obj.success) {

                        infoBar.classList.remove('alert-danger')
                        infoBar.classList.add('alert-success')
                        infoBar.innerHTML = '新增成功'
                        infoBar.style.display = 'block';
                        setTimeout(() => {
                            // infoBar.style.display = 'none';

                            //跳轉頁面回去read
                            location.href = 'http://localhost:8888/MFEE36_01/a_list_admin_TypeS.php';
                        }, 1500);



                    } else {
                        infoBar.classList.remove('alert-success')
                        infoBar.classList.add('alert-danger')
                        infoBar.innerHTML = '新增失敗'
                        infoBar.style.display = 'block';
                    }
                })
                .catch(ex => {
                    console.log(ex);
                    // infoBar.classList.remove('alert-success');
                    // infoBar.classList.add('alert-danger');
                    // infoBar.innerHTML = '新增發生錯誤';
                    // infoBar.style.display = 'block';
                    // setTimeout(() => {
                    //     infoBar.style.display = 'none';
                    // }, 2000);
                })

        } else {
            // 沒通過檢查
        }


    }
</script>
<?php include './partsNOEDIT/html-foot.php' ?>