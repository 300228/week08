<?php
require_once 'db.php'; // 連線資料庫

$sql = "SELECT * FROM event";
$result = mysqli_query($conn, $sql);

echo "<h2>活動資料表內容：</h2>";
echo "<table border='1' cellpadding='6'>";
echo "<tr><th>ID</th><th>活動名稱</th><th>活動說明</th></tr>";

while ($row = mysqli_fetch_assoc($result)) {
  echo "<tr>";
  echo "<td>".$row['id']."</td>";
  echo "<td>".$row['name']."</td>";
  echo "<td>".$row['description']."</td>";
  echo "</tr>";
}

echo "</table>";

mysqli_close($conn);
?>
