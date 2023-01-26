<?php
if($action == 'edit') {
  
  $query = "select * from main_playlist where id = :id limit 1";
  $row = db_query_one($query, ['id'=>$id]);

  if($_SERVER['REQUEST_METHOD'] == "POST" && $row) {
    $errors = [];
    //data validation
    if(empty($_POST['name'])) {
      $errors['name'] = "a name is required";
    }
    else if(!preg_match("/^[a-zA-Z \&\-]+$/", $_POST['name'])) {
      $errors['name'] = "enter a valid name name";
    }

    //validate image
    if(!empty($_FILES['image']['name'])) {
      $folder = "uploads/";
      if(!file_exists($folder)) {
        mkdir($folder,0777,true);
        file_put_contents($folder."index.php", "");
      }

      $allowed = ['image/jpeg','image/png'];
      if($_FILES['image']['error'] == 0 && in_array( $_FILES['image']['type'],$allowed)) {
        $destination = $folder.$_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], $destination);

        // deleting old image if new one was given in edit
        if(file_exists($row['image'])) {
          unlink($row['image']);
        }
      }
      else {
        $errors['name'] = "image not valid, allowed types are ".implode(",", $allowed);
      }
    }
 
    if(empty($errors)) {
      $values = [];
      $values['name'] = trim($_POST['name']);
      $values['user_id'] = user('id');
      // $values['bio'] = trim($_POST['bio']);
      $values['id'] = $id;
      $query = "update main_playlist set name = :name,user_id = :user_id where id = :id limit 1";
        
      if(!empty($destination)) {
        $query = "update main_playlist set name = :name,image = :image,user_id = :user_id where id = :id limit 1";
        $values['image'] = $destination;
      }
        
      db_query($query,$values);
      message("Playlist edited successfully.");
      redirect('admin/playlist');
      
    }
   
  }
}

?>

<?php require page('templates/admin-header')?>

    <section class="admin-content" style="min-height: 200px;">
     
    
       <!-- edit section -->
      <?php if($action == 'edit'):?>

        <div style="max-width: 500px; margin: auto;">
         <form method="post"  enctype="multipart/form-data">
          <h3>Edit Playlist</h3>

        <?php if(!empty($row)):?>
          <input class="form-control my-1" value="<?=set_value('name',$row['name'])?>" type="text" name="name" placeholder="Artistname">
          <?php if(!empty($errors['name'])): ?>
            <small class="error"><?=$errors['name']?></small>
          <?php endif; ?>

          <img src="<?=ROOT?>/<?=$row['image']?>" style="width: 200px; height: 200px; object-fit:cover;">
          
          <div style="padding-top:15px;">Playlist Image</div>
          <input class="form-control my-1" type="file" name="image">
          
           <!-- <label>Artist Bio</label>
          <textarea rows="10" name="bio" class="form-control my-1">
             <?=set_value('bio',$row['bio'])?>
          </textarea>  -->

          <button class="btn bg-clr my-1">Save</button>
          <a href="<?=ROOT?>/admin/playlist">
            <button style="margin-left: 20px;" type="button" class="btn bg-clr my-1">Back</button>
          </a>
        <?php else: ?>
          <div class="alert">That record was not found</div>
          <a href="<?=ROOT?>/admin/playlist">
            <button type="button" class="btn bg-clr my-1">Back</button>
          </a>
        <?php endif; ?>
         </form>
       </div>

       
       <!-- view songs in playlist -->
       <?php elseif($action == 'songs'):?>
         <div class="pratafont" style="font-size: 25px;">Songs in this playlist</div>

          <section class="content">
           <?php 
             $pid = $URL[3];
            //  $limit = 20;
            //  $offset = ($page - 1) * $limit;
             $query = "select * from songs where id in (select song_id from playlist_songs where id = :pid)";
             $rows = db_query($query,['pid'=>$pid]);

           ?>

      <?php if(!empty($rows)): ?>
        <?php foreach($rows as $row): ?>
           <?php include page('templates/song') ?>
        <?php endforeach; ?>
      <?php endif; ?>
    </section>
    
      <?php else:?>
       <?php  
          $id = user('id');
          $query = "select * from main_playlist where user_id = :id order by id desc limit 20";
          $rows = db_query($query,['id'=>$id]);
       ?>

       <h3>Playlists
        <a href="<?=ROOT?>/admin/playlist/add">
          <button style="margin-left: 20px;" class="btn bg-clr">Add New</button>
        </a>
       <?php  $action = $URL[2] ?? null;
       $id = $URL[3] ?? null; ?>
       </h3>
        <table class="table">
          <tr>
            <!-- <th>ID</th> -->
            <th> Playlist Name</th>
            <th>Cover</th>
            <th>Creator</th>
            <th>Edit</th>
          </tr>

          <?php if(!empty($rows)):?>
            <?php foreach($rows as $row):?>
              <tr>
               <!-- <td><?=$row['id']?></td> -->
               <td><?=$row['name']?></td>

               <td><a href="<?=ROOT?>/admin/playlist/<?=$row['id']?>"><img src="<?=ROOT?>/<?=$row['image']?>" style="width: 200px; height: 200px; object-fit:cover;"></a>
               <div><a href="<?=ROOT?>/admin/playlist/songs/<?=$row['id']?>">
               <button style="margin-left: 20px;" class="btn bg-clr my-2">Open Playlist</button>
               </a></div></td>
               <td><?=get_creator($row['user_id'])?></td>

               <td>
                <a href="<?=ROOT?>/admin/playlist/edit/<?=$row['id']?>">
                  <svg width="25" height="25" fill="white" class="bi bi-pencil-square" viewBox="0 0 16 16">
                  <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                  <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                  </svg>
                </a>

               </td>
             </tr>
             <?php endforeach; ?>
          <?php endif; ?>
          
        </table>

      <?php endif;?>

      
    </section>

    <?php require page('templates/admin-footer')?>
    