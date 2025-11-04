<?php
require_once "header.php";
// 管理員檢查：name='管理員' 且 role='M'
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$isAdmin = isset($_SESSION['role'], $_SESSION['name']) && $_SESSION['role'] === 'M' && $_SESSION['name'] === '管理員';
if (!$isAdmin) {
  echo '<div class="container mt-4">'
     . '<div class="alert alert-danger">只有管理員才能編輯職缺</div>'
     . '<a href="job.php" class="btn btn-primary">返回首頁</a>'
     . '</div>';
  require_once "footer.php";
  exit;
}
try {
  $postid = "";
  $company = "";
  $content = "";
  $pdate = "";
  if ($_GET) {
    require_once 'db.php';
    $action = $_GET["action"]??"";
    if ($action=="confirmed"){
      // update data (接收 POST 的 company, content)
      $postid = $_GET["postid"] ?? '';
      $company = $_POST['company'] ?? '';
      $content = $_POST['content'] ?? '';
      $sql="update job set company=?, content=? where postid=?";
      $stmt = mysqli_stmt_init($conn);
      mysqli_stmt_prepare($stmt, $sql);
      mysqli_stmt_bind_param($stmt, "ssi", $company, $content, $postid);
      $result = mysqli_stmt_execute($stmt);
      mysqli_close($conn);
      header('location:job.php');
      exit;
    }
    else{
      //show data
      $postid = $_GET["postid"];
      $sql="select postid, company, content, pdate from job where postid=?";    
      // $result = mysqli_query($conn, $sql);
      $stmt = mysqli_stmt_init($conn);
      mysqli_stmt_prepare($stmt, $sql);
      mysqli_stmt_bind_param($stmt, "i", $postid);
      $res = mysqli_stmt_execute($stmt);
      if ($res){
        mysqli_stmt_bind_result($stmt, $postid, $company, $content, $pdate);
        mysqli_stmt_fetch($stmt);
      }
    }//confirmed else
    mysqli_close($conn);
  }//$_GET
  // handle POST update
  if ($_POST) {
    require_once 'db.php';
    $postid = $_POST['postid'] ?? '';
    $company = $_POST['company'] ?? '';
    $content = $_POST['content'] ?? '';
    // update record
    if ($postid && $company && $content) {
      $sql = "update job set company=?, content=? where postid=?";
      $stmt = mysqli_stmt_init($conn);
      mysqli_stmt_prepare($stmt, $sql);
      mysqli_stmt_bind_param($stmt, "ssi", $company, $content, $postid);
      $ok = mysqli_stmt_execute($stmt);
      mysqli_close($conn);
      if ($ok) {
        header('Location: job.php');
        exit;
      } else {
        $err = '更新失敗';
      }
    }
  }
} catch(Exception $e) {
  echo 'Message: ' .$e->getMessage();
}
?>
<div class="container">
  <form action="job_update.php?postid=<?=urlencode($postid)?>&action=confirmed" method="post">
  <input type="hidden" name="postid" value="<?=htmlspecialchars($postid)?>">
  <div class="mb-3 row">
    <label for="_company" class="col-sm-2 col-form-label">求才廠商</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" name="company" id="_company" 
        placeholder="公司名稱" value="<?=htmlspecialchars($company)?>" required>
    </div>
  </div>
  <div class="mb-3">
    <label for="_content" class="form-label">求才內容</label>
    <textarea class="form-control" id="_content" name="content" 
      rows="10" required><?=htmlspecialchars($content)?></textarea>
  </div>
  <?php if (!empty($err)) { ?><div class="alert alert-danger"><?=htmlspecialchars($err)?></div><?php } ?>
  <button type="button" class="btn btn-secondary me-2" onclick="history.back()">返回</button>
  <input class="btn btn-primary" type="submit" value="送出">
  </form>
</div>
<?php
require_once "footer.php";
?>