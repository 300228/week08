<?php
include 'header.php';
require_once 'db.php'; // 連線資料庫

try {
    $sql = "SELECT * FROM event";
    $result = mysqli_query($conn, $sql);
?>
    <div class="container my-5">
        
        <div class="d-flex justify-content-end mt-4">
            <a href="activity_insert.php" class="btn btn-primary" role="button" aria-label="新增活動">+新增活動</a>
        </div>
        <div class="row mb-4">
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <div class="col-md-6 mb-4">
                    <div class="card h-100 bg-white text-dark">
                        <div class="card-body d-flex flex-column">
                            <h3 class="card-title"><?= htmlspecialchars($row['name']); ?></h3>
                            <p class="card-text">
                                <?= htmlspecialchars($row['description']); ?>
                            </p>
                            <div class="mt-auto">
                                <?php if (isset($row['id'])): ?>
                                    <a href="activity_update.php?postid=<?= urlencode($row['id']) ?>" class="btn btn-secondary btn-sm me-2">修改</a>
                                    <a href="activity_delete.php?postid=<?= urlencode($row['id']) ?>" class="btn btn-danger btn-sm">刪除</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        
    </div>

<?php
    mysqli_close($conn);
} catch (Exception $e) {
    echo "<p class='text-danger text-center'>無法載入活動資料：" . htmlspecialchars($e->getMessage()) . "</p>";
}
include('footer.php');
?>