<?php include 'header.php'; ?>
<?php
if ($_POST) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $name = $_SESSION["name"] ?? ($_POST["name"] ?? "N/A");
    $role = $_SESSION["role"] ?? ($_POST["role"] ?? "N/A");

    $dinner = isset($_POST["dinner"]) ? $_POST["dinner"] : "";

    // 計算金額
    $price = 0;
    $isFree = ($role === "teacher" || $role === "faculty" || $role === 'T' || $role === 'M');
    $isStudent = ($role === "student" || $role === 'S');
    if (!$isFree) {
        if ($isStudent) {
            $price += 100;
        }
        if ($dinner === "yes" || $dinner === "dinner") {
            $price += 80;
        }
        echo '<div class="alert alert-primary" role="alert">'
            . htmlspecialchars($name) . '(' . htmlspecialchars($role) . ')，您要繳交 ' . $price . ' 元'
            . '</div>';
    } else {
        echo '<div class="alert alert-success" role="alert">'
            . htmlspecialchars($name) . '(' . htmlspecialchars($role) . ')，您免費參加！'
            . '</div>';
    }
} else {
    header("Location: status.html");
}
?>

<?php include('footer.php'); ?>
