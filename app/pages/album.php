<?php require page('templates/header') ?>
    <center><div class="section-title">Songs in this Album</div></center>

    <section class="content">
      <?php 
          
          $id = $URL[1] ?? null;
          $query = "select * from album where id = :id limit 1";
          $row = db_query_one($query,['id'=>$id]);

      ?>

      <?php if(!empty($row)): ?>
        
           <?php require page('album-full') ?>
        
      <?php endif; ?>

    </section>

    <?php require page('templates/footer') ?>