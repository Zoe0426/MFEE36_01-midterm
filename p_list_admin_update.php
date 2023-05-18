<?php
require './partsNOEDIT/connect-db.php';
$title = '編輯';

$post_sid = isset($_GET['post_sid']) ? intval($_GET['post_sid']) : 0;
$sql = "SELECT * FROM `post_list_admin` WHERE `post_sid`={$post_sid}";

//按下編輯原始表單內容會出現在表單上
$upDate = $pdo->query($sql)->fetch(); //如果選到這一條就會post_sid=這個，如選到post_sid=180就會是這選到post_sid=180一整條的東西，像是 SELECT * FROM `post_list_admin` WHERE `post_sid`=180;
// print_r($upDate);
if (empty($upDate)) {
  header('Location: p_readPost_api.php');
  exit;
}


?>


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
<div class="col-auto col-md-10 mt-5">
  <div class="container p-form mt-5">
    <div class="row">
      <div class="col-6">
        <div class="card mt-3">
          <div class="card-body">

            <h5 class="card-title">編輯文章公告</h5>
            <form name="form1" onsubmit="checkForm(event)">
              <div class="mb-3">
                <input type="text" style="display:none" name="post_sid" value="<?= "$post_sid" ?>">
                <label for="admin_name">管理者名稱：</label>
                <select name="admin_name" id="admin_name" data-required="1">
                  <option selected value="--請選擇--">--請選擇--</option>
                  <option value="Lilian" <?= $upDate['admin_name'] == "Lilian" ? "selected" : "" ?>>Lilian</option>
                  <option value="Jenny" <?= $upDate['admin_name'] == "Jenny" ? "selected" : "" ?>>Jenny</option>
                  <option value="Gabrielle" <?= $upDate['admin_name'] == "Gabrielle" ? "selected" : "" ?>>Gabrielle</option>
                  <option value="Lily" <?= $upDate['admin_name'] == "Lily" ? "selected" : "" ?>>Lily</option>
                  <option value="Jill" <?= $upDate['admin_name'] == "Jill" ? "selected" : "" ?>>Jill</option>
                  <option value="Shu yi" <?= $upDate['admin_name'] == "Shu yi" ? "selected" : "" ?>>Shu yi</option>
                </select>
                <div class="form-text"></div>
              </div>
              <div class="mb-3">
                <label for="board_sid">看板：</label>
                <select name="board_sid" id="board_sid" data-required="1">
                  <?php foreach ($r_post as $r) : ?>
                    <!-- <option selected value="</option> -->
                    <option value="<?= $r['board_sid'] ?>" <?= $upDate['board_sid'] == $r['board_sid'] ? "selected" : "" ?>> <?= $r['board_name'] ?></option>
                  <?php endforeach; ?>
                </select>
                <div class="form-text"></div>
              </div>

              <div class="mb-3">
                <label for="post_title" class="form-label">文章標題：</label>
                <input type="text" name="post_title" id="post_title" data-required="1" value="<?= $upDate['post_title'] ?>" />
                <div class="form-text"></div>
              </div>
              <div class="mb-3">
                <label for="post_content" class="form-label">
                  文章內容：
                </label>
                <br />
                <textarea name="post_content" id="post_content" cols="30" rows="10" data-required="1"><?= $upDate['post_content'] ?></textarea>
                <div class="form-text"></div>
              </div>
              <div class="mb-3">
                <label for="file" class="form-label">檔案：</label>
                <input type="file" name="file" accept="image/jpeg" id="file">
              </div>

              <div class="alert alert-danger" role="alert" id="infoBar" style="display: none"></div>

              <button type="submit" class="btn btn-primary">確定</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php include './partsNOEDIT/script.php' ?>
  <script>
    const admin_name = document.querySelector('#admin_name');
    const board_sid = document.querySelector('#board_sid');
    const post_title = document.querySelector('#post_title');
    const post_content = document.querySelector('#post_content');
    const infoBar = document.querySelector('#infoBar');
    // 取得必填欄位
    const fields = document.querySelectorAll('form * [data-required="1"]');

    function checkForm(event) {
      event.preventDefault();

      for (let f of fields) {
        f.style.border = '1px solid blue';
        // console.log(f.nextElementSibling);
        f.nextElementSibling.innerHTML = '';
        // if (f.nextElementSibling) {
        //   f.nextElementSibling.innerHTML = '';
      }

      let isPass = true; // 預設值是通過的

      //跳出提示
      if (post_title.value === "") {
        isPass = false;

        post_title.style.border = '1px solid red';
        post_title.nextElementSibling.innerHTML = '請輸入文字';

        post_content.style.border = '1px solid red';
        post_content.nextElementSibling.innerHTML = '請輸入文字';
      }

      //沒有選到下拉式選單時 跳出提示
      if (admin_name.value === "--請選擇--") {
        isPass = false;
        admin_name.style.border = '1px solid red';
        admin_name.nextElementSibling.innerHTML = '請選擇';
      }

      if (board_sid.value === "--請選擇--") {
        isPass = false;
        board_sid.style.border = '1px solid red';
        board_sid.nextElementSibling.innerHTML = '請選擇';
      }

      if (isPass) {
        const fd = new FormData(document.form1); //沒有外觀的表單

        //infobar的東西
        fetch("p_update_api.php", {
            method: "POST",
            body: fd, // Content-Type 省略, multipart/form-data
          })
          .then((r) =>
            r.json()
          )
          .then((obj) => {
            // console.log(obj);
            if (obj.success) {
              infoBar.classList.remove("alert-danger");
              infoBar.classList.add("alert-success");
              infoBar.innerHTML = "編輯成功";
              infoBar.style.display = "block";
            } else {
              infoBar.classList.remove("alert-success");
              infoBar.classList.add("alert-danger");
              infoBar.innerHTML = "編輯失敗";
              infoBar.style.display = "block";
            }
            // setTime(() => {
            //   infoBar.style.display = "none";
            // }, 2000);

            //跳轉頁面回去read
            location.href = 'p_readPost_api.php';
          })
          .catch(ex => {
            console.log(ex);
            infoBar.classList.remove('alert-success');
            infoBar.classList.add('alert-danger');
            infoBar.innerHTML = '編輯發生錯誤';
            infoBar.style.display = 'block';
            // setTimeout(() => {
            //     infoBar.style.display = 'none';
            // }, 2000);
          })
      } else {
        // 沒通過檢查
      }

      //選擇項目後，提示消失
      admin_name.addEventListener('input', (event) => {
        if (event.target.value != "--請選擇--") {
          event.target.style.border = '1px solid #ccc';
          event.target.nextElementSibling.textContent = "";
        } else {
          event.target.style.border = '1px solid red';
          event.target.nextElementSibling.textContent = "請選擇";
        }
      })
      board_sid.addEventListener('input', (event) => {
        if (event.target.value != "--請選擇--") {
          event.target.style.border = '1px solid #ccc';
          event.target.nextElementSibling.textContent = "";
        } else {
          event.target.style.border = '1px solid red';
          event.target.nextElementSibling.textContent = "請選擇";
        }
      })

      //輸入東西之後，提示消失
      post_title.addEventListener('input', (event) => {
        if (event.target.value != "") {
          event.target.style.border = '1px solid #ccc';
          event.target.nextElementSibling.textContent = "";
        } else {
          event.target.style.border = '1px solid red';
          event.target.nextElementSibling.textContent = "請輸入文字";
        }
      })

      post_content.addEventListener('input', (event) => {
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