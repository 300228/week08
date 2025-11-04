<?php
require_once "header.php";
if (session_status() === PHP_SESSION_NONE) { session_start(); }
// 必須登入
if (!isset($_SESSION['account'])) {
    header('Location: login.php?from=profile.php');
    exit;
}
try {
    require_once 'db.php';
    $account = $_SESSION['account'];
    $name = $_SESSION['name'] ?? '';
    $role = $_SESSION['role'] ?? '';
    $err = '';
    $msg = '';

    if ($_POST) {
        $new_name = trim($_POST['name'] ?? '');
        $old_password = $_POST['old_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $new_password_confirm = $_POST['new_password_confirm'] ?? '';

        // 取得目前密碼
        $sql = "SELECT password FROM user WHERE account = ?";
        $stmt = mysqli_stmt_init($conn);
        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_bind_param($stmt, "s", $account);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $current_password);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        $update_password = false;
        // 處理密碼更改邏輯：若 new_password 非空，表示使用者想改密碼
        if ($new_password !== '') {
            // 需要舊密碼且舊密碼正確
            if ($old_password === '') {
                $err .= "請輸入舊密碼以更改密碼。<br>";
            } elseif ($old_password !== $current_password) {
                $err .= "舊密碼不正確，密碼未變更。<br>";
            } elseif ($new_password !== $new_password_confirm) {
                $err .= "兩次新密碼輸入不相同，密碼未變更。<br>";
            } else {
                $update_password = true;
            }
        }

        // 若沒有錯誤，執行更新（名稱必填且非空）
        if ($err === '') {
            // 決定 SQL
            if ($update_password) {
                $sql = "UPDATE user SET name = ?, password = ? WHERE account = ?";
                $stmt = mysqli_stmt_init($conn);
                mysqli_stmt_prepare($stmt, $sql);
                mysqli_stmt_bind_param($stmt, "sss", $new_name, $new_password, $account);
            } else {
                $sql = "UPDATE user SET name = ? WHERE account = ?";
                $stmt = mysqli_stmt_init($conn);
                mysqli_stmt_prepare($stmt, $sql);
                mysqli_stmt_bind_param($stmt, "ss", $new_name, $account);
            }
            $ok = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            if ($ok) {
                $_SESSION['name'] = $new_name;
                $name = $new_name;
                $msg = '資料已更新';
            } else {
                $err .= '更新失敗，請稍後再試。';
            }
        }

        mysqli_close($conn);
    } else {
        // GET: 取得使用者資料（顯示 name）
        $sql = "SELECT name, role FROM user WHERE account = ?";
        $stmt = mysqli_stmt_init($conn);
        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_bind_param($stmt, "s", $account);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $db_name, $db_role);
        if (mysqli_stmt_fetch($stmt)) {
            $name = $db_name;
            $role = $db_role;
        }
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    }
} catch (Exception $e) {
    $err = '發生錯誤：' . $e->getMessage();
}
?>

<div class="container mt-4">
  <h2>個人資料</h2>
  <form method="post" action="profile.php">
    <div class="mb-3 row">
      <label class="col-sm-2 col-form-label">帳號</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" value="<?=htmlspecialchars($account)?>" disabled>
      </div>
    </div>
    <div class="mb-3 row">
      <label class="col-sm-2 col-form-label">身分</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" value="<?=htmlspecialchars($role)?>" disabled>
      </div>
    </div>
    <div class="mb-3 row">
      <label for="_name" class="col-sm-2 col-form-label">姓名</label>
      <div class="col-sm-10">
        <input type="text" name="name" id="_name" class="form-control" value="<?=htmlspecialchars($name)?>" required>
      </div>
    </div>

    <hr>
    <p>若要修改密碼，請輸入舊密碼與新密碼（需重複輸入新密碼）。如不修改密碼，請留空新密碼欄位。</p>
    <div class="mb-3">
      <label for="old_password" class="form-label">舊密碼</label>
      <input type="password" name="old_password" id="old_password" class="form-control">
    </div>
    <div class="mb-3">
      <label for="new_password" class="form-label">新密碼</label>
      <input type="password" name="new_password" id="new_password" class="form-control">
    </div>
    <div class="mb-3">
      <label for="new_password_confirm" class="form-label">再次輸入新密碼</label>
      <input type="password" name="new_password_confirm" id="new_password_confirm" class="form-control">
    </div>

    <?php if (!empty($err)): ?>
      <div class="alert alert-danger"><?= $err ?></div>
    <?php endif; ?>
    <?php if (!empty($msg)): ?>
      <div class="alert alert-success"><?= $msg ?></div>
    <?php endif; ?>

    <button type="button" class="btn btn-secondary me-2" onclick="history.back()">返回</button>
    <button type="submit" class="btn btn-primary">更新資料</button>
  </form>
</div>

<?php require_once 'footer.php'; ?>
