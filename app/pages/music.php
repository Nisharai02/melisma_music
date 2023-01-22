<?php require page('templates/header') ?>
    <div class="section-title">Music</div>

    <section class="content">
      <?php 
          $limit = 5;
          $offset = ($page - 1) * $limit;
          $rows = db_query("select * from songs order by views desc limit $limit offset $offset");

      ?>

      <?php if(!empty($rows)): ?>
        <?php foreach($rows as $row): ?>
           <?php include page('templates/song') ?>
        <?php endforeach; ?>
      <?php endif; ?>
    </section>
    <section class="content" style="padding-bottom: 20px;">
    <a href="<?=ROOT?>/music?page=<?=$prev_page?>">
       <button class="btn bg-clr">prev</button>
    </a>
    <a href="<?=ROOT?>/music?page=<?=$next_page?>">
       <button class="float-end btn bg-clr">next</button>
    </a>
    </section>
    
    <?php require page('templates/footer') ?>