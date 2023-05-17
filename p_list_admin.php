<?php
require './partsNOEDIT/connect-db.php' ?>
<?php include './partsNOEDIT/html-head.php' ?>
<style>
  .form-text {
    color: red;
  }
</style>

<?php include './partsNOEDIT/navbar.php' ?>

<!-- 下拉列表 -->
<?php
$sql_post = "SELECT * FROM `post_board`";
$stmt = $pdo->query($sql_post);
$r_post = $stmt->fetchAll();
// print_r($r_post);
// var_dump($r_post);


?>
<div class="col-auto col-md-10 mt-3">
  <div class="container">
    <div class="row">
      <div class="col-6">
        <div class="card">
          <div class="card-body">

            <h5 class="card-title">新增文章公告</h5>
            <form name="form1" onsubmit="checkForm(event)">
              <div class="mb-3">
                <label for="admin_name">管理者名稱：</label>
                <select name="admin_name" id="admin_name" data-required="1">
                  <option selected value="--請選擇--">--請選擇--</option>
                  <option value="Lilian">Lilian</option>
                  <option value="Jenny">Jenny</option>
                  <option value="Gabrielle">Gabrielle</option>
                  <option value="Lily">Lily</option>
                  <option value="Jill">Jill</option>
                  <option value="Shu yi">Shu yi</option>
                </select>
                <div class="form-text"></div>
              </div>
              <div class="mb-3">
                <label for="board_name">看板：</label>
                <select name="board_name" id="board_name" data-required="1">
                  <option selected value="--請選擇--">--請選擇--</option>
                  <?php foreach ($r_post as $r) : ?>
                    <option value="<?= $r['board_sid'] ?>"><?= $r['board_name'] ?></option>
                  <?php endforeach; ?>
                </select>
                <div class="form-text"></div>
              </div>

              <div class="mb-3">
                <label for="post_title" class="form-label">文章標題：</label>
                <input type="text" name="post_title" id="post_title" data-required="1" />
                <div class="form-text"></div>
              </div>
              <div class="mb-3">
                <label for="post_content" class="form-label">
                  文章內容：
                </label>
                <br />
                <textarea name="post_content" id="post_content" cols="30" rows="10" data-required="1"></textarea>
                <div class="form-text"></div>
              </div>
              <div class="mb-3">
                <label for="file" class="form-label">檔案：</label>
                <input type="file" name="file" accept="image/jpeg" id="file">
              </div>

              <div class="alert alert-danger" role="alert" id="infoBar" style="display: none"></div>

              <button type="submit" class="btn btn-primary">新增</button>
            </form>
            <!-- 要顯示在頁面中，送資料給api的form -->
            <!-- <form name="form2" onsubmit="checkForm(event)">
              <div class="w-25 me-3" onclick="postAddImg()" id="postImg">
                <img src="" alt="" id="imginfo">按此新增照片/選完照片後，圖片會顯示在此
              </div>
              <input type="text" name="post_img" id="post_img">
            </form> -->
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php include './partsNOEDIT/script.php' ?>
  <script>
    const adminName = document.querySelector('#admin_name');
    const boardName = document.querySelector('#board_name');
    const postTitle = document.querySelector('#post_title');
    const postContent = document.querySelector('#post_content');
    const infoBar = document.querySelector('#infoBar');

    console.log(adminName);
    console.log(boardName);
    console.log(postTitle);
    console.log(adminName);
    console.log(adminName);
    console.log(adminName);
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
      if (postTitle.value === "") {
        isPass = false;

        postTitle.style.border = '1px solid red';
        postTitle.nextElementSibling.innerHTML = '請輸入文字';

        postContent.style.border = '1px solid red';
        postContent.nextElementSibling.innerHTML = '請輸入文字';
      }

      //沒有選到下拉式選單時 跳出提示
      if (adminName.value === "--請選擇--") {
        isPass = false;
        adminName.style.border = '1px solid red';
        adminName.nextElementSibling.innerHTML = '請選擇';
      }

      if (boardName.value === "--請選擇--") {
        isPass = false;
        boardName.style.border = '1px solid red';
        boardName.nextElementSibling.innerHTML = '請選擇';
      }

      if (isPass) {
        const fd = new FormData(document.form1); //沒有外觀的表單

        //infobar的東西
        fetch("p_addPost-api.php", {
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
            location.href = 'http://localhost:8888/project-forum/MFEE36_01/p_readPost_api.php';
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

      //選擇項目後，提示消失
      adminName.addEventListener('input', (event) => {
        if (event.target.value != "--請選擇--") {
          event.target.style.border = '1px solid #ccc';
          event.target.nextElementSibling.textContent = "";
        } else {
          event.target.style.border = '1px solid red';
          event.target.nextElementSibling.textContent = "請選擇";
        }
      })
      boardName.addEventListener('input', (event) => {
        if (event.target.value != "--請選擇--") {
          event.target.style.border = '1px solid #ccc';
          event.target.nextElementSibling.textContent = "";
        } else {
          event.target.style.border = '1px solid red';
          event.target.nextElementSibling.textContent = "請選擇";
        }
      })

      //輸入東西之後，提示消失
      postTitle.addEventListener('input', (event) => {
        if (event.target.value != "") {
          event.target.style.border = '1px solid #ccc';
          event.target.nextElementSibling.textContent = "";
        } else {
          event.target.style.border = '1px solid red';
          event.target.nextElementSibling.textContent = "請輸入文字";
        }
      })

      postContent.addEventListener('input', (event) => {
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