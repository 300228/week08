
<?php
require_once "header.php";
// 管理員檢查：name='管理員' 且 role='M'
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$isAdmin = isset($_SESSION['role'], $_SESSION['name']) && $_SESSION['role'] === 'M' && $_SESSION['name'] === '管理員';
if (!$isAdmin) {
  echo '<div class="container mt-4">'
     . '<div class="alert alert-danger">只有管理員才能刪除活動</div>'
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
                // delete data
                $id = $_GET["postid"];
                $sql="delete from event where id=?";
                $stmt = mysqli_stmt_init($conn);
                mysqli_stmt_prepare($stmt, $sql);
                mysqli_stmt_bind_param($stmt, "i", $id);
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
    <table class="table table-bordered table-striped">
        <tr>
            <td>編號</td>
            <td>活動名稱</td>
            <td>活動描述</td>
            <td>日期</td>
        </tr>
        <tr>
            <td><?=$postid?></td>
            <td><?=htmlspecialchars($name)?></td>
            <td><?=htmlspecialchars($description)?></td>
            <td><?=htmlspecialchars($pdate)?></td>
        </tr>
    </table>
    <button type="button" class="btn btn-secondary me-2" onclick="history.back()">返回</button>
    <a href="activity_delete.php?postid=<?=$postid?>&action=confirmed" class="btn btn-danger">刪除</a>
</div>
<?php
require_once "footer.php";
?>
