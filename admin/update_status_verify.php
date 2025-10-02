<?php
include_once "conn.php";

// รับค่าจาก GET
$news_id = isset($_GET['news_id']) ? (int)$_GET['news_id'] : 0;
$status = isset($_GET['status']) ? (int)$_GET['status'] : null;

// ตรวจสอบค่าที่ได้รับ
if ($news_id > 0 && ($status === 0 || $status === 1)) {
    // อัปเดตสถานะข่าวสาร
    $sql = "UPDATE news SET news_status = :status WHERE news_id = :news_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':status', $status, PDO::PARAM_INT);
    $stmt->bindParam(':news_id', $news_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header("Location: ./?id=news");
        exit;
    } else {
        echo "เกิดข้อผิดพลาดในการอัปเดตสถานะ";
    }
} else {
    echo "ข้อมูลไม่ถูกต้อง";
}
?>