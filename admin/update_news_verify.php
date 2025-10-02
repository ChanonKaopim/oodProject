<?php
include_once "conn.php";

$news_id = $_POST["news_id"];
$news_title = $_POST["news_title"];
$news_detail = $_POST["news_detail"];
$news_type = $_POST["news_type"];

// ตรวจสอบว่ามีการอัพโหลดรูปภาพใหม่หรือไม่
if (isset($_FILES['news_img']) && $_FILES['news_img']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = "../news/"; // โฟลเดอร์เก็บภาพ
    $tmpName = $_FILES['news_img']['tmp_name'];
    $originalName = basename($_FILES['news_img']['name']);
    
    // สร้างชื่อไฟล์ใหม่ ป้องกันชื่อซ้ำ เช่น ใส่ timestamp
    $ext = pathinfo($originalName, PATHINFO_EXTENSION);
    $newFileName = uniqid('news_', true) . "." . strtolower($ext);
    $uploadFile = $uploadDir . $newFileName;

    // ย้ายไฟล์ที่อัพโหลดไปยังโฟลเดอร์ที่ต้องการ
    if (move_uploaded_file($tmpName, $uploadFile)) {
        // อัปเดตรูปภาพใหม่ในฐานข้อมูล

        // ก่อนอัปเดต ลบรูปเก่าของข่าวนี้ (ถ้ามี)
        $stmtOldImg = $conn->prepare("SELECT news_img FROM news WHERE news_id = ?");
        $stmtOldImg->execute([$news_id]);
        $oldImg = $stmtOldImg->fetchColumn();
        if ($oldImg && file_exists($uploadDir . $oldImg)) {
            unlink($uploadDir . $oldImg);
        }

        // อัปเดตข้อมูลพร้อมรูปใหม่
        $sql = "UPDATE news SET news_title = ?, news_detail = ?, news_type = ?, news_img = ? WHERE news_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$news_title, $news_detail, $news_type, $newFileName, $news_id]);

        header("Location: ./?id=news");
        exit;
    } else {
        echo "เกิดข้อผิดพลาดในการอัพโหลดไฟล์ภาพ";
        exit;
    }
} else {
    // ไม่มีการอัปโหลดรูปภาพใหม่ อัปเดตแค่ข้อมูลอื่น ๆ
    $sql = "UPDATE news SET news_title = ?, news_detail = ?, news_type = ? WHERE news_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$news_title, $news_detail, $news_type, $news_id]);

    header("Location: ./?id=news");
    exit;
}
?>