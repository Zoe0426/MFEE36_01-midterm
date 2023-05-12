<?php include './partsNOEDIT/connect-db.php' ?>
<?php include './partsNOEDIT/html-head.php' ?>
<?php include './partsNOEDIT/navbar.php' ?>

<!-- 下拉列表 -->
<?php 
$sql_post="SELECT * FROM `post_list_admin`";
$r_post=$pdo->query($sql_post)->fetchAll();

?>
<div class="col-auto col-md-10 mt-3">
<div class="container">
      <div class="row">
        <div class="col-6">
          <div class="card">
            <div class="card-body">
            <?php print_r($r_post) ?>  
            <h5 class="card-title">新增資料</h5>
              <form name="form1" onsubmit="checkForm(event)">
                <div class="mb-3">
                  <label for="admin_name">管理者名稱：</label>
                  <select name="admin_name" id="admin_name">
                    <?php foreach ($r_post as $r) : ?>
                      <option value="<?= $r['admin_name'] ?>"><?= $r['admin_name'] ?></option>
                    <?php endforeach; ?>
                    <!-- <option value="Lilian">Lilian</option>
                    <option value="Jenny">Jenny</option>
                    <option value="Gabrielle">Gabrielle</option>
                    <option value="Lily">Lily</option>
                    <option value="">Jill</option>
                    <option value="">Shu yi</option> -->
                  </select>
                </div>
                <div class="mb-3">
                  <label for="board_sid" class="form-label">看板編號：</label>
                  <input type="text" name="board_sid" id="board_sid" />
                </div>
                <div class="mb-3">
                  <label for="board_name">看板：</label>
                  <select name="board_name" id="board_name">
                    <option value="">醫療版</option>
                    <option value="">貓/狗聚版</option>
                    <option value="">有趣版</option>
                  </select>
                </div>

                <div class="mb-3">
                  <label for="post_title" class="form-label">文章標題：</label>
                  <input type="text" name="post_title" id="post_title" />
                </div>
                <div class="mb-3">
                  <label for="post_content" class="form-label"
                    >文章內容：</label
                  >
                  <br />
                  <textarea
                    name="post_content"
                    id="post_content"
                    cols="30"
                    rows="10"
                  ></textarea>
                </div>
                <!-- <div class="mb-3">
                  <label for="birthday" class="form-label">文章類型</label>
                  <input type="text" name="post_type" id="post_type" />
                </div>
                <div class="mb-3">
                  <label for="address" class="form-label">毛孩編號</label>
                  <input type="text" name="pet_sid" id="pet_sid" />
                </div> -->

                <div
                  class="alert alert-danger"
                  role="alert"
                  id="infoBar"
                  style="display: none"
                ></div>

                <button type="submit" class="btn btn-primary">新增</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

<?php include './partsNOEDIT/script.php' ?>
<script>
        function checkForm(event) {
        const fd = new FormData(document.form1);
        fetch("addPost-api.php", {
          method: "POST",
          body: fd, // Content-Type 省略, multipart/form-data
        })
          .then((r) => {
            console.log(r.json());
          })
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
            setTimeout(() => {
              infoBar.style.display = "none";
            }, 2000);
          });
      }


</script>
<?php include './partsNOEDIT/html-foot.php' ?>