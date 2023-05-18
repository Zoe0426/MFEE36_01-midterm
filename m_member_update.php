<?php
require './partsNOEDIT/connect-db.php';

$member_sid = isset($_GET["member_sid"]) ? $_GET["member_sid"] : '';
$sql = "SELECT * FROM `mem_member` WHERE `member_sid`=:member_sid";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':member_sid', $member_sid, PDO::PARAM_STR);
$stmt->execute();
$r = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<?php include './partsNOEDIT/html-head.php' ?>
<?php include './partsNOEDIT/navbar.php' ?>
<style>
    form .mb-3 .form-text {
        color: red;
    }
</style>
<div class="container">
    <form name="m_coupon_update" onsubmit="coupon_update_form(event)">
        <input type="hidden" class="form-control" id="member_sid" aria-describedby="emailHelp" name="member_sid" value="<?= $r['member_sid'] ?>">
        <div class="mb-3">
            <label for="member_name" class="form-label">會員姓名</label>
            <input type="text" class="form-control" id="member_name" aria-describedby="emailHelp" name="member_name" value="<?= $r['member_name'] ?>" data-required="1">
            <div id="form-text" class="form-text"></div>
        </div>
        <div class="mb-3">
            <label for="member_email" class="form-label">Email</label>
            <input type="text" class="form-control" id="member_email" aria-describedby="emailHelp" name="member_email" value="<?= $r['member_email'] ?>" data-required="1">
            <div id="form-text" class="form-text"></div>
        </div>
        <div class="mb-3">
            <label for="member_password" class="form-label">密碼</label>
            <input type="text" class="form-control" id="member_password" aria-describedby="emailHelp" name="member_password" value="<?= $r['member_password'] ?>" data-required="1">
            <div id="form-text" class="form-text"></div>
        </div>
        <div class="mb-3">
            <label for="member_mobile" class="form-label">手機</label>
            <input type="text" class="form-control" id="member_mobile" aria-describedby="emailHelp" name="member_mobile" value="<?= $r['member_mobile'] ?>" data-required="1">
            <div id="form-text" class="form-text"></div>
        </div>
        <div class="mb-3">
            <label for="member_gender" class="form-label">性別</label>
            <!-- <input type="text" class="form-control" id="member_gender" aria-describedby="emailHelp" name="member_gender" value="<?= $r['member_gender'] ?>" data-required="1"> -->

            <select name="member_gender" id="member_gender">
                <option value="男" <?= $r['member_gender'] == "男" ? 'selected' : '' ?>>男</option>
                <option value="女" <?= $r['member_gender'] == "女" ? 'selected' : '' ?>>女</option>
            </select>
            <div id="form-text" class="form-text"></div>
        </div>
        <div class="mb-3">
            <label for="member_birth" class="form-label">生日</label>
            <input type="date" class="form-control" id="member_birth" aria-describedby="emailHelp" name="member_birth" value="<?= $r['member_birth'] ?>" data-required="1">
            <div id="form-text" class="form-text"></div>
        </div>
        <div class="mb-3">
            <label for="member_pet" class="form-label">寵物</label>
            <!-- <input type="text" class="form-control" id="member_pet" aria-describedby="emailHelp" name="member_pet" value="<?= $r['member_pet'] ?>" data-required="1"> -->
            <select name="member_pet" id="member_pet">
                <option value="狗" <?= $r['member_pet'] == "狗" ? 'selected' : '' ?>>狗</option>
                <option value="貓" <?= $r['member_pet'] == "貓" ? 'selected' : '' ?>>貓</option>
                <option value="狗貓" <?= $r['member_pet'] == "狗貓" ? 'selected' : '' ?>>狗貓</option>
                <option value="其他" <?= $r['member_pet'] == "其他" ? 'selected' : '' ?>>其他</option>
            </select>
            <div id="form-text" class="form-text"></div>
        </div>
        <div class="mb-3">
            <label for="member_level" class="form-label">會員等級</label>
            <!-- <input type="text" class="form-control" id="member_level" aria-describedby="emailHelp" name="member_level" value="<?= $r['member_level'] ?>" data-required="1"> -->

            <select name="member_level" id="member_level">
                <option value="金牌" <?= $r['member_level'] == "金牌" ? 'selected' : '' ?>>金牌</option>
                <option value="銀牌" <?= $r['member_level'] == "銀牌" ? 'selected' : '' ?>>銀牌</option>
                <option value="銅牌" <?= $r['member_level'] == "銅牌" ? 'selected' : '' ?>>銅牌</option>
            </select>
            <div id="form-text" class="form-text"></div>
        </div>
        <div class="mb-3">
            <label for="member_ID" class="form-label">會員ID</label>
            <input type="text" class="form-control" id="member_ID" aria-describedby="emailHelp" name="member_ID" value="<?= $r['member_ID'] ?>" data-required="1">
            <div id="form-text" class="form-text"></div>
        </div>
        <div class="mb-3">
            <label for="update_time" class="form-label">更新時間</label>
            <input type="text" class="form-control" id="update_time" aria-describedby="emailHelp" name="update_time" value="<?= $r['update_time'] ?>" data-required="1" disabled>
            <div id="form-text" class="form-text"></div>
        </div>
        <div class="mb-3">
            <label for="create_time" class="form-label">入會時間</label>
            <input type="text" class="form-control" id="create_time" aria-describedby="emailHelp" name="create_time" value="<?= $r['create_time'] ?>" data-required="1" disabled>
            <div id="form-text" class="form-text"></div>
        </div>
        <div class="alert alert-danger" role="alert" id="infoBar" style="display:none"></div>
        <button type="submit" class="btn btn-primary">修改</button>
    </form>
</div>

<?php include './partsNOEDIT/script.php' ?>
<script>
    const infoBar = document.querySelector('#infoBar');
    const fields = document.querySelectorAll('form *[data-required="1"]');

    function coupon_update_form(event) {
        event.preventDefault();

        // 恢復原狀
        for (let f of fields) {
            f.style.border = '1px solid #ccc';
            f.nextElementSibling.innerHTML = ''
        }

        // TODO: 檢查欄位資料
        for (let f of fields) {
            if (!f.value) {
                isPass = false;
                f.style.border = '1px solid red';
                f.nextElementSibling.innerHTML = '請填入資料'
            }
        }

        const fd = new FormData(document.m_coupon_update);
        fetch('m_member_update-api.php', {
                method: 'POST',
                body: fd,
            }).then(r => r.json())
            .then(obj => {
                console.log(obj);
                if (obj.success) {
                    infoBar.classList.remove('alert-danger');
                    infoBar.classList.add('alert-success');
                    infoBar.innerHTML = '編輯成功';
                    infoBar.style.display = 'block';
                    setTimeout(() => {
                        location.href = 'm_member-list.php';
                    }, 1000)
                } else {

                }
            })
    }
</script>
<?php include './partsNOEDIT/html-foot.php' ?>