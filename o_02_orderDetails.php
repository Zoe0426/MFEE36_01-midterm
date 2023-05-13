<?php
require './partsNOEDIT/connect-db.php' ?>
<?php include './partsNOEDIT/html-head.php' ?>
<?php include './partsNOEDIT/navbar.php' ?>
<h1>hello details</h1>

<?php include './partsNOEDIT/script.php' ?>
<script>
    fetch('XXX_api.php', { //這邊請填入自己要連結的api名稱
            method: 'POST',
            body: fd,
        }).then(r => r.json())
        .then(obj => {
            if (obj.filename) {
                const finalImg = document.querySelector('#finalImg');
                const pro_img = document.querySelector('#pro_img');
                finalImg.firstChild.src = `./shopImgs/${obj.filename}`;
                finalImg.firstChild.style.display = "block";
                pro_img.value = obj.filename;
            }
        })
</script>
<?php include './partsNOEDIT/html-foot.php' ?>