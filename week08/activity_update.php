
<?php
require_once "header.php";
// 管理員檢查：name='管理員' 且 role='M'
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$isAdmin = isset($_SESSION['role'], $_SESSION['name']) && $_SESSION['role'] === 'M' && $_SESSION['name'] === '管理員';
if (!$isAdmin) {
  echo '<div class="container mt-4">'
     . '<div class="alert alert-danger">只有管理員才能編輯活動</div>'
     . '<a href="index.php" class="btn btn-primary">返回首頁</a>'
     . '</div>';
  require_once "footer.php";
  exit;
}
try {
    $postid = "";
    $name = "";
    $description = "";
    $pdate = "";
        if ($_GET) {
            require_once 'db.php';
            $action = $_GET["action"]??"";
            if ($action=="confirmed"){
                // update data (expect POST: name, description)
                $postid = $_GET["postid"] ?? '';
                $name = $_POST['name'] ?? '';
                $description = $_POST['description'] ?? '';
                $sql = "update event set name=?, description=? where id=?";
                $stmt = mysqli_stmt_init($conn);
                mysqli_stmt_prepare($stmt, $sql);
                mysqli_stmt_bind_param($stmt, "ssi", $name, $description, $postid);
                $result = mysqli_stmt_execute($stmt);
                mysqli_close($conn);
                header('location:index.php');
                exit;
            }
            else{
                // show data
                $id = $_GET["postid"];
                $sql="select id, name, description from event where id=?";    
                $stmt = mysqli_stmt_init($conn);
                mysqli_stmt_prepare($stmt, $sql);
                mysqli_stmt_bind_param($stmt, "i", $id);
                $res = mysqli_stmt_execute($stmt);
                if ($res){
                    mysqli_stmt_bind_result($stmt, $id, $name, $description);
                    mysqli_stmt_fetch($stmt);
                    // 保持與顯示變數一致
                    $postid = $id;
                }
            }
        mysqli_close($conn);
    }
} catch(Exception $e) {
    echo 'Message: ' .$e->getMessage();
}
?>
<div class="container">
    <h2 class="mt-4">編輯活動</h2>
    <form action="activity_update.php?postid=<?=urlencode($postid)?>&action=confirmed" method="post">
        <input type="hidden" name="postid" value="<?=htmlspecialchars($postid)?>">
        <div class="mb-3 row">
            <label for="_name" class="col-sm-2 col-form-label">活動名稱</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="name" id="_name" placeholder="活動名稱" value="<?=htmlspecialchars($name)?>" required>
            </div>
        </div>
        <div class="mb-3">
            <label for="_description" class="form-label">活動描述</label>
            <textarea class="form-control" id="_description" name="description" rows="10" required><?=htmlspecialchars($description)?></textarea>
        </div>
    <?php if (!empty($err)) { ?><div class="alert alert-danger"><?=htmlspecialchars($err)?></div><?php } ?>
    <button type="button" class="btn btn-secondary me-2" onclick="history.back()">返回</button>
    <input class="btn btn-primary" type="submit" value="送出">
    </form>
</div>
<?php
require_once "footer.php";
?>
