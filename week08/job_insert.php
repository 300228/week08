<?php
require_once "header.php";
// 管理員檢查：name='管理員' 且 role='M'
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$isAdmin = isset($_SESSION['role'], $_SESSION['name']) && $_SESSION['role'] === 'M' && $_SESSION['name'] === '管理員';
if (!$isAdmin) {
  echo '<div class="container mt-4">'
     . '<div class="alert alert-danger">只有管理員才能新增職缺</div>'
     . '<a href="job.php" class="btn btn-primary">返回首頁</a>'
     . '</div>';
  require_once "footer.php";
  exit;
}
try {
  require_once 'db.php';
  $msg="";
  if ($_POST) {
    // insert data
    $company = $_POST["company"];
    $content = $_POST["content"];
    $sql="insert into job (company, content, pdate) values (?, ?, now())";
    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $company, $content);
    $result = mysqli_stmt_execute($stmt);
    if ($result) {
      header('location:job.php');
      exit;
    }
    else {
      $msg = "無法新增資料";
    }
  }
?>

<div class="container">
<form action="job_insert.php" method="post">
  <div class="mb-3 row">
    <label for="_company" class="col-sm-2 col-form-label">求才廠商</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" name="company" id="_company" placeholder="公司名稱" required>
    </div>
  </div>
  <div class="mb-3">
    <label for="_content" class="form-label">求才內容</label>
    <textarea class="form-control" name="content" id="_content" rows="10" required></textarea>
  </div>
  <button type="button" class="btn btn-secondary me-2" onclick="history.back()">返回</button>
  <input class="btn btn-primary" type="submit" value="送出">
  <?=$msg?>
</form>
</div>

<?php
  mysqli_close($conn);
}
//catch exception
catch(Exception $e) {
  echo 'Message: ' .$e->getMessage();
}
require_once "footer.php";
?>