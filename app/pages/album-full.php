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
      <center style="padding-top: 15px;padding-bottom: 10px;">Songs in this Album</center>
      <div style="display: flex;flex-wrap: wrap;justify-content: center;">
         <?php 
             $query = "select * from songs where album_id = :album_id order by views desc limit 30";
          $songs = db_query($query,['album_id'=>$row['id']]);

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