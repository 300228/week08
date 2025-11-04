<?php include 'header.php'; ?>
<?php
if ($_POST) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $program_price = array("1"=>150, "2"=>100, "3"=>60, "N/A"=>0);
    $programlist = $_POST["program"] ?? ["N/A"];
    $name = $_SESSION["name"] ?? ($_POST["name"] ?? "N/A");
    $role = $_SESSION["role"] ?? ($_POST["role"] ?? "N/A");
    $price = 0;

    // 教師或管理員免付費（管理員 role 為 'M'）
    $isFree = ($role === "teacher" || $role === "faculty" || $role === 'T' || $role === 'M');
    if (!$isFree) {
        foreach ($programlist as $program) {
            $price += $program_price[$program] ?? 0;
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
    header("Location: conference.html");
    exit;
}
?>
<?php include('footer.php'); ?>
