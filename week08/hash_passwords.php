<?php
// 一次性腳本：將 user 表的明碼密碼改為 password_hash
// 安全性提醒：執行前請備份資料庫。執行完畢請刪除此檔或移出公開目錄。
define('CONFIRM_HASH', true);
// 為避免被誤觸發，需要把下面常數設為 true 才會執行更新
if (!defined('CONFIRM_HASH') || CONFIRM_HASH !== true) {
    echo "未啟用執行，請在檔案中定義 define('CONFIRM_HASH', true); 再執行。\n";
    exit;
}

require_once 'db.php';

$sql = "SELECT * FROM user";
$result = mysqli_query($conn, $sql);
if (!$result) {
    echo "查詢失敗: " . mysqli_error($conn) . "\n";
    mysqli_close($conn);
    exit;
}

$count = 0;
$skipped = 0;
$errors = 0;
while ($row = mysqli_fetch_assoc($result)) {
    $account = $row['account'];
    $plainPassword = $row['password'];

    // 簡單檢查是否已為 hash（bcrypt/argon2 常見前綴）
    if (preg_match('/^\$2[ayb]\$|^\$argon2/', $plainPassword)) {
        $skipped++;
        continue;
    }

    $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);
    if ($hashedPassword === false) {
        $errors++;
        continue;
    }

    $updateSql = "UPDATE user SET password='" . mysqli_real_escape_string($conn, $hashedPassword) . "' WHERE account='" . mysqli_real_escape_string($conn, $account) . "'";
    if (mysqli_query($conn, $updateSql)) {
        $count++;
    } else {
        $errors++;
        echo "更新失敗 (account={$account}): " . mysqli_error($conn) . "\n";
    }
}

echo "完成。已更新: {$count} 筆, 已跳過(已為 hash): {$skipped} 筆, 錯誤: {$errors} 筆\n";

mysqli_close($conn);

?>
