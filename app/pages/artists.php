<?php require page('templates/header') ?>
    <div class="section-title">Music</div>

    <section class="content">
      <?php 

          $rows = db_query("select * from artists order by id desc limit 30");

      ?>

      <?php if(!empty($rows)): ?>
        <?php foreach($rows as $row): ?>
           <?php include page('templates/artist') ?>
        <?php endforeach; ?>
      <?php endif; ?>

    </section>

<?php require page('templates/footer') ?>