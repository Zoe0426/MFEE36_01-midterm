<?php include './parts/html-head.php' ?>
<?php include './parts/navbar.php' ?>
<div class="col-auto col-md-10 mt-3">
    內容在這兒喔
</div>
<?php include './parts/script.php' ?>
<script>
    function checkForm(event) { //function名可以自己決定喔！

        event.preventDefault();
        let isPass = true; // 預設值是通過的

        // TODO: 驗證表格內容，若不通過，isPass ＝false； 

        if (isPass) { //格式完全正確，呼叫api

            fetch('xxx_api.php', {
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
</script>
<?php include './parts/html-foot.php' ?>