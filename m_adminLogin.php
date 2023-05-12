<?php include './partsNOEDIT/html-head.php' ?>
<?php include './partsNOEDIT/navbar.php' ?>
<style>

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
                            <input type="text" class="form-control" id="account" aria-describedby="emailHelp" name="account">
                            <div class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">密碼</label>
                            <input type="password" class="form-control" id="password" name="password">
                            <div class="form-text"></div>
                        </div>
                        <div class="alert alert-danger" role="alert" style="display:none">
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
    function checkForm() {
        event.preventDefault();

        const fd = new FormData(document.form1);
        fetch('m_adminLogin.php', {
                method: 'POST',
                body: fd,
            }).then(r => r.json())
            .then(obj => {

            })
    }
</script>

<?php include './partsNOEDIT/html-foot.php' ?>