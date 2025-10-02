<?php
$news_id = $_GET["news_id"];
$news_img = $_GET["news_img"];
include_once "conn.php";
$sql = "DELETE FROM news WHERE news_id = :news_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':news_id', $news_id, PDO::PARAM_INT);
$stmt->execute();
$img_path = "../news/" . $news_img;
if (file_exists($img_path)) {
    unlink($img_path);
}
header("Location: ./?id=news");
exit;
?>