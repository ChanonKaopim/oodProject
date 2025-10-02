<?php
$target_dir = "../";
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($_FILES["news_img"]["name"], PATHINFO_EXTENSION));

// สร้างชื่อไฟล์ใหม่แบบสุ่ม (ใช้เวลา + uniqid เพื่อความไม่ซ้ำ)
$new_filename = 'pic/news_' . time() . '_' . uniqid() . '.' . $imageFileType;
$target_file = $target_dir . $new_filename;

// ตรวจสอบว่าเป็นไฟล์รูปภาพ
if (isset($_POST["submit"])) {
    $check = getimagesize($_FILES["news_img"]["tmp_name"]);
    if ($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}

// ตรวจสอบขนาดไฟล์
if ($_FILES["news_img"]["size"] > 5000000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}

// ตรวจสอบประเภทไฟล์
if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}

// ถ้าไม่มีปัญหาให้ทำการอัปโหลด
if ($uploadOk == 1) {
    if (move_uploaded_file($_FILES["news_img"]["tmp_name"], $target_file)) {
        // อัปโหลดสำเร็จ
        include_once "conn.php";

        $news_title  = $_POST["news_title"] ?? '';
        $news_detail = $_POST['news_detail'] ?? '';
        $news_type   = $_POST['news_type'] ?? '';

        // บันทึกชื่อไฟล์ใหม่ในฐานข้อมูล
        $stmt = $conn->prepare("
            INSERT INTO news (news_title, news_detail, news_type, news_img)
            VALUES (:news_title, :news_detail, :news_type, :news_img)
        ");

        $success = $stmt->execute([
            ':news_title'  => $news_title,
            ':news_detail' => $news_detail,
            ':news_type'   => $news_type,
            ':news_img'    => $new_filename
        ]);

        if ($success) {
            header("Location: ./?id=news");
            exit;
        } else {
            echo "เกิดข้อผิดพลาด: ไม่สามารถเพิ่มข้อมูลได้";
        }
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
} else {
    echo "Sorry, your file was not uploaded.";
}
?>