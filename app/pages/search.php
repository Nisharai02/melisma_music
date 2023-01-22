<?php require page('templates/header') ?>
    <div class="section-title">Results for '<?=$_GET['find']?>'</div>

    <section class="content">
      <?php 
          $title = $_GET['find'] ?? null;
          if(!empty($title)) {
            $title = "%$title%";
            $query = "select * from songs where title like :title order by views desc limit 24";
            $rows = db_query($query,['title'=>$title]);
          }

      ?>

      <?php if(!empty($rows)): ?>
        <?php foreach($rows as $row): ?>
           <?php include page('templates/song') ?>
        <?php endforeach; ?>
        <?php else: ?>
          <div class="my-2">No songs found</div>
      <?php endif; ?>

    </section>

    <?php require page('templates/footer') ?>