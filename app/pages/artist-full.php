<!-- music card -->
<div class="music-card-full" style="max-width: 800px;">
  <center class="card-title my-2"><?=esc($row['name'])?></center>
  <center>
  <div style="overflow: hidden;height: 400px;width:400px;
  object-fit: cover;">
    <img src="<?=ROOT?>/<?=$row['image']?>"></a>
  </div>
  </center>
  
  <div class="card-content">
      <div class="my-2 mx-2" style="background-color: #9F8772;color: #222222;padding: 20px 20px;border-radius: 5%;"><?=esc($row['bio'])?></div>

      <center style="padding-top: 15px;padding-bottom: 10px;">Songs by this Artist:</center>
      <div style="display: flex;flex-wrap: wrap;justify-content: center;">
         <?php 
             $query = "select * from songs where artist_id = :artist_id order by views desc limit 30";
          $songs = db_query($query,['artist_id'=>$row['id']]);

         ?>

         <?php if(!empty($songs)): ?>
           <?php foreach($songs as $row): ?>
              <?php include page('templates/song') ?>
           <?php endforeach; ?>
         <?php endif; ?>
      </div>

  </div>
</div>
<!-- music card end -->