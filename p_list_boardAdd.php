<?php
// require './partsNOEDIT/admin-require.php';
require './partsNOEDIT/connect-db.php' ?>
<?php include './partsNOEDIT/html-head.php' ?>
<style>
  .form-text {
    color: red;
  }
</style>

<?php include './partsNOEDIT/navbar.php' ?>

<div class="col-auto col-md-10 mt-3">
  <div class="container">
    <div class="row">
      <div class="col-6">
        <div class="card">
          <div class="card-body">

            <h5 class="card-title">新增文章公告</h5>
            <form name="form1" onsubmit="checkForm(event)">
              <div class="mb-3">
                <label for="board_name">看板名稱：</label>
                <input type="text" name="board_name" id="board_name" data-required="1" value="">
                <div class="form-text"></div>
              </div>
              <div class="alert alert-danger" role="alert" id="infoBar" style="display: none"></div>

              <button type="submit" class="btn btn-primary">新增</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php include './partsNOEDIT/script.php' ?>
  <script>
    const board_name = document.querySelector('#board_name');
    const infoBar = document.querySelector('#infoBar');

    console.log(board_name);
    // 取得必填欄位
    const fields = document.querySelectorAll('form *[data-required="1"]');

    function checkForm(event) {
      event.preventDefault(); //避免submit就先送出

      for (let f of fields) {
        f.style.border = '1px solid blue';
        f.nextElementSibling.innerHTML = '';
      }

      let isPass = true; //預設值是通過的

      //跳出提示
      if (board_name.value === "") {
        isPass = false;

        board_name.style.border = '1px solid red';
        board_name.nextElementSibling.innerHTML = '請輸入文字';
      }

      if (isPass) {
        const fd = new FormData(document.form1); //沒有外觀的表單

        //infobar的東西
        fetch("p_addBoard-api.php", {
            method: "POST", //資料傳遞的方式
            body: fd, // Content-Type 省略, multipart/form-data
          })
          .then((r) =>
            r.json()
          )
          .then((obj) => {
            console.log(obj);
            if (obj.success) {
              infoBar.classList.remove("alert-danger");
              infoBar.classList.add("alert-success");
              infoBar.innerHTML = "新增成功";
              infoBar.style.display = "block";
            } else {
              infoBar.classList.remove("alert-success");
              infoBar.classList.add("alert-danger");
              infoBar.innerHTML = "新增失敗";
              infoBar.style.display = "block";
            }
            // setTime(() => {
            //   infoBar.style.display = "none";
            // }, 2000);

            //跳轉頁面回去read
            location.href = 'p_readPost_board.php';
          })
          .catch(ex => {
            console.log(ex);
            infoBar.classList.remove('alert-success');
            infoBar.classList.add('alert-danger');
            infoBar.innerHTML = '新增發生錯誤';
            infoBar.style.display = 'block';
            // setTimeout(() => {
            //     infoBar.style.display = 'none';
            // }, 2000);
          })
      } else {
        // 沒通過檢查
      }

      //輸入東西之後，提示消失
      board_name.addEventListener('input', (event) => {
        if (event.target.value != "") {
          event.target.style.border = '1px solid #ccc';
          event.target.nextElementSibling.textContent = "";
        } else {
          event.target.style.border = '1px solid red';
          event.target.nextElementSibling.textContent = "請輸入文字";
        }
      })



    }
  </script>
  <?php include './partsNOEDIT/html-foot.php' ?>