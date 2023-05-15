<?php
require './partsNOEDIT/connect-db.php';
$title = '編輯';

$post_sid = isset($_GET['post_sid']) ? intval($_GET['post_sid']) : 0;
$sql = "SELECT * FROM `post_list_admin` WHERE `post_sid`={$post_sid}";

$upDate = $pdo->query($sql)->fetch();
print_r($upDate);
if (empty($upDate)) {
  header('Location: p_readPost_api.php');
  exit;
}
?>


<?php include './partsNOEDIT/html-head.php' ?>
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

            <h5 class="card-title">編輯文章公告</h5>
            <form name="form1" onsubmit="checkForm(event)">
              <div class="mb-3">
                <label for="admin_name">管理者名稱：</label>
                <select name="admin_name" id="admin_name" data-required="1">
                  <option value="Lilian">Lilian</option>
                  <option value="Jenny">Jenny</option>
                  <option value="Gabrielle">Gabrielle</option>
                  <option value="Lily">Lily</option>
                  <option value="">Jill</option>
                  <option value="">Shu yi</option>
                </select>
              </div>
              <!-- <div class="mb-3">
                  <label for="board_sid" class="form-label">看板編號：</label>
                  <input type="text" name="board_sid" id="board_sid" />
                </div> -->

              <div class="mb-3">
                <label for="board_name">看板：</label>
                <select name="board_sid" id="board_sid" data-required="1">
                  <?php foreach ($r_post as $r) : ?>
                    <option value="<?= $r['board_sid'] ?>"><?= $r['board_name'] ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="mb-3">
                <label for="post_title" class="form-label">文章標題：</label>
                <input type="text" name="post_title" id="post_title" data-required="1" value="<?= $upDate['post_title'] ?>" />
              </div>
              <input type="hidden" name="post_sid" id="post_sid" data-required="1" value="<?= $upDate['post_sid'] ?>" />
              <div class="mb-3">
                <label for="post_content" class="form-label">
                  文章內容：
                </label>
                <br />
                <textarea name="post_content" id="post_content" cols="30" rows="10" data-required="1"><?= $upDate['post_content'] ?></textarea>
              </div>
              <!-- 這個需要隱藏，這是上傳圖片用的form -->
              <div class="mb-3">
                <label for="file" class="form-label">檔案：</label>
                <input type="file" name="tempImg" accept="image/jpeg" id="tempImg">
              </div>

              <div class="alert alert-danger" role="alert" id="infoBar" style="display: none"></div>

              <button type="submit" class="btn btn-primary">儲存</button>
            </form>
            <!-- 要顯示在頁面中，送資料給api的form -->
            <form name="form2" onsubmit="checkForm(event)">
              <div class="w-25 me-3" onclick="postAddImg()" id="postImg">
                <img src="" alt="" id="imginfo">按此新增照片/選完照片後，圖片會顯示在此
              </div>
              <input type="text" name="post_img" id="post_img">
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php include './partsNOEDIT/script.php' ?>
  <script>
    const post_title = document.querySelector('#post_title');
    const infoBar = document.querySelector('#infoBar');
    // 取得必填欄位
    const fields = document.querySelectorAll('form1 * [data-required="1"]');

    function checkForm(event) {
      event.preventDefault();

      for (let f of fields) {
        f.style.border = '1px solid #ccc';
        // console.log(f.nextElementSibling);
        if (f.nextElementSibling) {
          f.nextElementSibling.innerHTML = '';
        }

      }
      post_title.style.border = '1px solid #ccc';
      if (post_title.nextElementSibling) {
        post_title.nextElementSibling.innerHTML = '';
      }

      let isPass = true; // 預設值是通過的

      // TODO: 檢查欄位資料

      // 檢查必填欄位
      for (let f of fields) {
        if (!f.value) {
          isPass = false;
          f.style.border = '1px solid red';
          f.nextElementSibling.innerHTML = '請填入資料'
        }
      }


      if (isPass) {
        const fd = new FormData(document.form1);
        fetch("p_update_api.php", {
            method: "POST",
            body: fd, // Content-Type 省略, multipart/form-data
          })
          .then((r) => {
            console.log(r.json());
          })
          .then((obj) => {
            console.log(obj);

            location.href = 'http://localhost:8888/project-forum/MFEE36_01/p_readPost_api.php';
          });
      }
    }

    //===新增主照片+API===
    const tempImg = document.querySelector("#tempImg");

    function postAddImg() {
      //模擬點擊
      tempImg.click();
    }
    tempImg.addEventListener("change", () => {
      event.preventDefault();
      const fd2 = new FormData(document.form1);
      fetch("p_file_api.php", {
          method: 'POST',
          body: fd2,
        }).then(r => r.json())
        .then(obj => {
          if (obj.filename) {
            const postImg = document.querySelector('#postImg');
            const post_img = document.querySelector('#post_img');
            postImg.firstChild.src = `./postImg/${obj.filename}`;
            postImg.firstChild.style.display = "block";
            post_img.value = obj.filename;
          }
        }).catch(ex => {
          console.log(ex);
        })
    })
  </script>
  <?php include './partsNOEDIT/html-foot.php' ?>