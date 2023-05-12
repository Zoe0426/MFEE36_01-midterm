<?php
require './partsNOEDIT/connect-db.php' ?>
<?php include './partsNOEDIT/html-head.php' ?>
<?php include './partsNOEDIT/navbar.php' ?>
<div class="container pt-4">
    <form name="getmem" onsubmit="getMemCart(event)">
        <div class="mb-3">
            <label for="sbname" class="form-label">姓名</label>
            <input type="text" class="form-control" id="sbname" name="sbname">
        </div>
        <div class="mb-3">
            <label for="sbmobile" class="form-label">手機</label>
            <input type="text" class="form-control" id="sbmobile" name="sbmobile">
        </div>
        <div class="mb-3">
            <label for="sbmemsid" class="form-label">會員編號</label>
            <input type="text" class="form-control" id="sbmemsid" name="sbmemsid">
        </div>

        <button type="submit" class="btn btn-primary">搜尋</button>
    </form>
</div>


<?php include './partsNOEDIT/script.php' ?>
<script>
    function getMemCart(e) {
        const fd = new FormData(document.getmem);
        fetch('o_getMemCart_api.php', { //這邊請填入自己要連結的api名稱
                method: 'POST',
                body: fd,
            }).then(r => r.json())
            .then(obj => console.log(obj))
            .catch(ex => {
                console.log(ex)
            })
    }
</script>
<?php include './partsNOEDIT/html-foot.php' ?>