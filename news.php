<div class="container py-3">
    <?php
    include "admin/conn.php";
    $sql_type = "SELECT * FROM news_type";
    $stmt_type = $conn->prepare($sql_type);
    $stmt_type->execute();
    $result_type = $stmt_type->fetchAll(PDO::FETCH_ASSOC);

    foreach ($result_type as $row_type){
        $news_type_id = $row_type['news_type_id'];
    ?>

            <!--=============================// การ์ด Card //================================ -->
            <h2><?=$row_type['news_type_name']?></h2>

            <div class="row row-cols-1 row-cols-md-3 g-4 mb-5">
                <?php
                    $sql_news = "SELECT * FROM news WHERE news_type=$news_type_id";
                    $stmt_news = $conn->prepare($sql_news);
                    $stmt_news->execute();
                    $result_news = $stmt_news->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($result_news as $row_news){ if ($row_news['news_status'] == 0){continue;}

                ?>
                <div class="col">
                    <div class="card h100">
                        <img src="pic/<?=$row_news['news_img']?>" class="card-img-top">
                        <div class="card-body">
                            <h5 class="card-title"><?=$row_news['news_title']?></h5>
                        </div>
                        <div class="card-footer">
                            <small class="text-body-secondary"><?=$row_news['news_date']?></small> 
                        </div>
                    </div>
                </div>
                
                <?php
                    }
                ?>

            </div>
            <!--=============================\ ปิด Card \================================ -->
    <?php 
    } 
    ?>
</div>