<?php require page('templates/header') ?>
    <section>
      <img class="bg" src="<?=ROOT?>/assets/images/bg.jpeg">
    </section>
    
    <div class="section-title">Featured</div>

    <section class="content">
      <?php 

          $rows = db_query("select * from songs where featured = 1 order by id desc limit 20");

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