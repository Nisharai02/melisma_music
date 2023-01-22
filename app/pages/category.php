<?php require page('templates/header') ?>
    <div class="section-title">Music</div>
    
    <section class="content">
      <?php 
          $category = $URL[1] ?? null;
          $query = "select * from songs where category_id in (select id from categories where category = :category) order by views desc limit 50";
          $rows = db_query($query,['category'=>$category]);

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