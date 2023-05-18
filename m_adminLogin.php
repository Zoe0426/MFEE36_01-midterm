<?php include './partsNOEDIT/html-head.php' ?>
<?php include './partsNOEDIT/navbar.php' ?>
<style>
    form .mb-3 .form-text {
        color: red;
    }
</style>
<div class="container">
    <div class="row d-flex justify-content-center">
        <div class="col-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">管理員登入</h5>
                    <form name="form1" onsubmit="checkForm(event)">
                        <div class="mb-3">
                            <label for="account" class="form-label">帳號</label>
                            <input type="text" class="form-control" id="account" aria-describedby="emailHelp" name="account" data-required="1">
                            <div class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">密碼</label>
                            <input type="password" class="form-control" id="password" name="password" data-required="1">
                            <div class="form-text"></div>
                        </div>
                        <div class="alert alert-danger" role="alert" style="display:none" id="infoBar">
                        </div>
                        <button type="submit" class="btn btn-primary">登入</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<?php include './partsNOEDIT/script.php' ?>
<script>
    const infoBar = document.querySelector('#infoBar');
    const fields = document.querySelectorAll('form *[data-required="1"]');

    function checkForm(e) {
        e.preventDefault();

        // 恢復原狀
        for (let f of fields) {
            f.style.border = '1px solid #ccc';
            f.nextElementSibling.innerHTML = ''
        }

        let isPass = true; // 預設值是通過的

        // TODO: 檢查欄位資料
        for (let f of fields) {
            if (!f.value) {
                isPass = false;
                f.style.border = '1px solid red';
                f.nextElementSibling.innerHTML = '請填入資料'
            }
        }

        if (isPass) {
            const fd = new FormData(document.form1);
            fetch('m_adminLogin-api.php', {
                    method: 'POST',
                    body: fd
                }).then(r => r.json())
                .then(obj => {
                    console.log(obj);
                    if (obj.success) {
                        infoBar.classList.remove('alert-danger');
                        infoBar.classList.add('alert-success');
                        infoBar.innerHTML = '登入成功';
                        infoBar.style.display = 'block';
                        setTimeout(() => {
                            location.href = 'index_noEdit.php';
                        }, 1000)
                    } else {
                        infoBar.classList.remove('alert-success');
                        infoBar.classList.add('alert-danger');
                        infoBar.innerHTML = '帳號密碼錯誤';
                        infoBar.style.display = 'block';
                    }
                })
        }
    }
</script>

<?php include './partsNOEDIT/html-foot.php' ?>