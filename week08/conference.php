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
  <form action="conference_process.php" method="post">
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
    <div class="row">
      <div class="col">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="1" name="program[]" id="program_1" checked="checked">
          <label class="form-check-label" for="program_1">
            上午場 ($150)
          </label>
        </div>
      </div>
      <div class="col">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="2" name="program[]" id="program_2">
          <label class="form-check-label" for="program_2">
            下午場 ($100)
          </label>
        </div>
      </div>
      <div class="col">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="3" name="program[]" id="program_3" checked="checked">
          <label class="form-check-label" for="program_3">
            午餐 ($60)
          </label>
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
