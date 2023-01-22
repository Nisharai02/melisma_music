<div class="music-card">
  <div style="overflow: hidden">
    <a href="<?=ROOT?>/song/<?=$row['slug']?>"><img src="<?=ROOT?>/<?=$row['image']?>"></a>
  </div>
  <div class="card-content">
      <div class="card-title my-1"><?=esc($row['title'])?></div>
      <div class="card-subtitle my-1"><?=esc(get_artist($row['artist_id']))?></div>
      <div class="card-subtitle my-1" style="font-size: 15px;">genre: <?=esc(get_category($row['category_id']))?></div>
  </div>
</div>

