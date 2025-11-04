<?php
$title = "活動列表";
require_once "header.php";
try {
  require_once 'db.php';
  $order = $_POST["order"]??"";
  $searchtxt = mysqli_real_escape_string($conn, $_POST["searchtxt"] ?? "");
  $where = [];
  if ($searchtxt) {
    $where[] = "(name like '%$searchtxt%' or description like '%$searchtxt%')";
  }
  // 使用 event 表（與 index.php 一致）
  $sql = "select * from event";
  if (count($where) > 0) {
    $sql .= " where " . implode(' and ', $where);
  }
  if ($order) {
    $sql .= " order by $order";
  }
  $result = mysqli_query($conn, $sql);
?>
<div class="container">
  <form action="activity.php" method="post">
  <div class="row g-2 align-items-center mb-2">
    <div class="col-auto">
      <select name="order" aria-label="選擇排序欄位" class="form-select">
        <option selected value="">選擇排序欄位</option>
        <option value="name" <?=($order=="name")?'selected':''?>>活動名稱</option>
        <option value="description" <?=($order=="description")?'selected':''?>>活動描述</option>
      </select>
    </div>
    <div class="col-auto">
      <input placeholder="搜尋名稱或描述" value="<?=htmlspecialchars($searchtxt)?>" type="text" name="searchtxt" class="form-control">
    </div>
    <div class="col-auto">
      <input class="btn btn-primary" type="submit" value="搜尋">
    </div>
  </div>
</form>
<table class="table table-bordered table-striped" id="activity_table">
 <thead>
   <tr>
    <th>主辦單位</th>
    <th>活動內容</th>
    <th>日期</th>
   </tr>
 </thead>
 <tbody>
 <?php
 while($row = mysqli_fetch_assoc($result)) {
   // 支援不同主鍵欄位名稱(postid 或 id)
   $pk = isset($row['postid']) ? 'postid' : (isset($row['id']) ? 'id' : null);
 ?>
 <tr>
  <td><?=htmlspecialchars($row["name"] ?? $row["company"])?></td>
  <td><?=htmlspecialchars($row["description"] ?? $row["content"])?></td>
  <td><?=isset($row["pdate"]) ? htmlspecialchars($row["pdate"]) : ''?></td>
 </tr>
 <?php
  }
 ?>
 </tbody>
</table>
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
