<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['account'])) {
  header('Location: login.php?from=' . urlencode($_SERVER['REQUEST_URI']));
  exit;
}
include 'header.php';
?>
<div class="container mt-5">
  <form action="statu_process.php" method="post">
    <div class="row">
      <div class="col-6">
        <div class="form-floating mb-3">
          <input type="text" class="form-control" id="_name" name="name" value="<?=htmlspecialchars($_SESSION['name'])?>" readonly>
          <label for="_name">姓名</label>
        </div>
      </div>
      <div class="col-6">
        <div class="form-floating mb-3">
          <input type="text" class="form-control" id="_role" name="role" value="<?=htmlspecialchars($_SESSION['role'])?>" readonly>
          <label for="_role">身分</label>
        </div>
      </div>
    </div>
    <div class="row mb-3">
      <div class="col">
        <label class="form-label">晚餐：</label><br/>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="dinner" id="dinner_yes" value="yes" checked>
          <label class="form-check-label" for="dinner_yes">需要</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="dinner" id="dinner_no" value="no">
          <label class="form-check-label" for="dinner_no">不需要</label>
        </div>
      </div>
    </div>
    <input class="btn btn-primary" type="submit" value="Submit" />
    <a href="index.php" class="btn btn-secondary ms-2">返回</a>
  </form>
</div>
</body>
</html>
<?php include('footer.php'); ?>
