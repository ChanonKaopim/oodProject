<div class="container">
    <?php

        include "admin/conn.php";
        $sql = "SELECT news.*, news_type.news_type_name FROM news INNER JOIN news_type ON news.news_type = news_type.news_type_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $row) {
            echo "ID: " . $row['news_id'] . "<br>";
            echo "หัวข้อข่าว: " . $row['news_title'] . "<br>";
            echo "รายละเอียด: " . $row['news_detail'] . "<br>";
            echo "รูปภาพ: " . $row['news_img'] . "<br>";
            echo "ประเภท: " . $row['news_type_name'] . "<br>";
            echo "วันที่: " . $row['news_date'] . "<br>";
            echo "ผู้เขียน: " . $row['news_admin'] . "<hr>";
        }
    ?>
</div>