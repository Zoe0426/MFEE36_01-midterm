<?php include './parts/html-head.php' ?>
<style>
    #shopForm1,
    #pro_img {
        /* 要記得將傳照片的form1表單與回傳照片名稱隱藏起來*/
        display: none;
    }

    #finalImg {
        /* 設計自己要放的照片框框 */
        border: 3px dashed lightgray;
        position: relative;
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
<?php include './parts/navbar.php' ?>
<div class="col-auto col-md-10 mt-3">

    <!-- 這個需要隱藏，這是上傳圖片用的form -->
    <form name="form1" id="shopForm1">
        <input type="file" name="tempImg" accept="image/jpeg" id="tempImg">
    </form>

    <!-- 要顯示在頁面中，送資料給api的form -->
    <form name="form2" onsubmit="checkForm(event)">
        <div class="w-25 me-3" onclick="shopAddMainImg()" id="finalImg"><img src="" alt="" id="imginfo">按此新增照片/選完照片後，圖片會顯示在此</div>
        <input type="text" name="pro_img" id="pro_img">
    </form>

    <script>
        //表格內容送出的function,fetch請自己寫(參考add-api.php)
        function checkForm(event) {
            event.preventDefault();
        }

        //===新增主照片+API===
        const tempImg = document.querySelector("#tempImg");

        function shopAddMainImg() {
            //模擬點擊
            tempImg.click();
        }
        tempImg.addEventListener("change", () => {
            const fd = new FormData(document.form1);
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
                }).catch(ex => {
                    console.log(ex)
                })
        })
    </script>




</div>
<?php include './parts/script.php' ?>
<script>


</script>
<?php include './parts/html-foot.php' ?>