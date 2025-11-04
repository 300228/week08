<!DOCTYPE html>
<html lang="zh-Hant">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>登入</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include 'header.php'; ?>
<div class="container mt-5">
<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
require_once 'db.php'; 

// 使用資料庫驗證
if ($_POST) {
  $account = $_POST["account"] ?? "";
  $password = $_POST["password"] ?? "";
  $from = $_GET["from"] ?? $_POST["from"] ?? "";

  // 使用 mysqli_real_escape_string 避免 SQL injection
  $account_esc = mysqli_real_escape_string($conn, $account);
  $password_esc = mysqli_real_escape_string($conn, $password);

  $sql = "select * from user where account = '$account_esc' and password = '$password_esc'";
  $result = mysqli_query($conn, $sql);

  if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $_SESSION["account"] = $row['account'];
    $_SESSION["name"] = $row['name'] ?? $row['account'];
    $_SESSION["role"] = $row['role'] ?? '';
    // 登入後導向對應頁面
    if ($from) {
      header("Location: " . urldecode($from));
    } else {
      header("Location: index.php");
    }
    exit;
  } else {
    $msg = "帳號或密碼錯誤";
    header("Location: login.php?msg=" . urlencode($msg) . ($from ? "&from=" . urlencode($from) : ""));
    exit;
  }
} else {
  $msg = $_GET["msg"] ?? "";
  $from = $_GET["from"] ?? "";
?>
  <div class="row justify-content-center">
    <div class="col-md-4">
      <div class="card shadow">
        <div class="card-body">
          <h4 class="card-title mb-4">登入</h4>
          <form method="post" action="login.php<?php echo $from ? '?from=' . urlencode($from) : ''; ?>">
            <div class="mb-3">
              <label for="account" class="form-label">帳號</label>
              <input type="text" class="form-control" id="account" name="account" required>
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">密碼</label>
              <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">登入</button>
          </form>
          <?php if ($msg): ?>
            <div class="alert alert-danger mt-3" role="alert">
              <?=htmlspecialchars($msg)?>
            </div>
          <?php endif; ?>
          <?php if (isset($_SESSION["account"])): ?>
            <a href="logout.php" class="btn btn-outline-secondary w-100 mt-3">登出</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

<?php
}
?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php include('footer.php'); ?>

