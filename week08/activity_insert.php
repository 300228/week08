<?php
require_once "header.php";
// 管理員檢查：name='管理員' 且 role='M'
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$isAdmin = isset($_SESSION['role'], $_SESSION['name']) && $_SESSION['role'] === 'M' && $_SESSION['name'] === '管理員';
if (!$isAdmin) {
  echo '<div class="container mt-4">'
     . '<div class="alert alert-danger">只有管理員才能新增活動</div>'
     . '<a href="index.php" class="btn btn-primary">返回首頁</a>'
     . '</div>';
  require_once "footer.php";
  exit;
}
try {
  require_once 'db.php';
  $msg="";
  if ($_POST) {
    // 與 event 表欄位對應：name, description
    $name = $_POST["name"] ?? '';
    $description = $_POST["description"] ?? '';
    $sql="insert into event (name, description) values (?, ?)";
    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $name, $description);
    $result = mysqli_stmt_execute($stmt);
    if ($result) {
      header('location:index.php');
      exit;
    }
    else {
      $msg = "無法新增資料";
    }
  }
?>

<div class="container">
<form action="activity_insert.php" method="post">
  <div class="mb-3 row">
    <label for="_name" class="col-sm-2 col-form-label">活動名稱</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" name="name" id="_name" placeholder="活動名稱" required>
    </div>
  </div>
  <div class="mb-3">
    <label for="_description" class="form-label">活動描述</label>
    <textarea class="form-control" name="description" id="_description" rows="10" required></textarea>
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
