<?php include './partsNOEDIT/html-head.php' ?>
<?php include './partsNOEDIT/navbar.php' ?>

<form name="form1" onsubmit="checkForm(event)">
    <div class="mb-3">
        <div class="mb-3">
            <label for="act_name" class="form-label">活動標題</label>
            <input type="text" class="form-control" id="act_name" name="act_name">
            <div class="form-text"></div>
        </div>
    </div>
    <div class="mb-3">
        <label for="type_sid" class="form-label">活動類型</label>
        <select class="form-select" name="type_sid">
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
        <select class="form-select" name="group_time">
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
    <!-- <div class="mb-3">
        <label for="act_pic" class="form-label">照片上傳</label>
        <input class="form-control" type="file" id="act_pic" name="act_pic">
    </div> -->
    <div class="mb-3">
        <label for="act_content" class="form-label">活動內容</label>
        <textarea class="form-control" id="act_content" name="act_content"></textarea>
    </div>
    <div class="mb-3">
        <label for="act_policy" class="form-label">活動規範</label>
        <textarea class="form-control" id="act_policy" name="act_policy"></textarea>
    </div>

    <!-- <div class="alert alert-danger" role="alert" id="infoBar" style="display:none"></div> -->

    <button type="submit" class="btn btn-primary">新增</button>
</form>

<?php include './partsNOEDIT/script.php' ?>

<script>
    const nameField = document.querySelector('#act_name');
    const infoBar = document.querySelector('#infoBar');
    // 取得必填欄位
    const fields = document.querySelectorAll('form *[data-required="1"]');

    function checkForm(event) {
        event.preventDefault();

        // for (let f of fields) {
        //     f.style.border = '1px solid #ccc';
        //     f.nextElementSibling.innerHTML = ''
        // }
        // nameField.style.border = '1px solid #CCC';
        // nameField.nextElementSibling.innerHTML = ''

        // let isPass = true; // 預設值是通過的


        // if (nameField.value.length < 2) {
        //     isPass = false;
        //     nameField.style.border = '1px solid red';
        //     nameField.nextElementSibling.innerHTML = '請輸入至少兩個字'
        // }

        // if (isPass) {
        const fd = new FormData(document.form1); // 沒有外觀的表單

        // const usp = new URLSearchParams(fd); // 可以轉換為 urlencoded 格式
        // console.log(usp.toString());
        console.log(fd);
        fetch('a_add_api.php', {
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

                } else {
                    infoBar.classList.remove('alert-success')
                    infoBar.classList.add('alert-danger')
                    infoBar.innerHTML = '新增失敗'
                    infoBar.style.display = 'block';
                }
                setTimeout(() => {
                    infoBar.style.display = 'none';
                }, 2000);
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

        // } else {
        //     // 沒通過檢查
        // }


    }
</script>

<?php include './partsNOEDIT/html-foot.php' ?>