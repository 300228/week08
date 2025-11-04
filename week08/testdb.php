<?php
$servername = "localhost";
$dbname = "practice";
$dbUsername = "root";
$dbPassword = "";
try {
  $conn = mysqli_connect($servername, $dbUsername, $dbPassword, $dbname);
  // echo "成功連線!";
  $sql="select * from job";
  $result = mysqli_query($conn, $sql);
  print_r($result);
  mysqli_close($conn);
}
catch(Exception $e) {
  echo "無法連線: {$e->getMessage()}";
}
?>

<?php
while($row = mysqli_fetch_assoc($result)) {
  echo $row["company"];
  echo $row["content"];
  echo $row["pdate"];
}
?>