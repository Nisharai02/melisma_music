<?php

 if($action == 'add') {

    if($_SERVER['REQUEST_METHOD'] == "POST") {
      $errors = [];
      //data validation
      if(empty($_POST['title'])) {
        $errors['title'] = "a title is required";
      }
      else if(!preg_match("/^[a-zA-Z0-9 \.\&\-\+]+$/", $_POST['title'])) {
        $errors['title'] = "enter a valid title";
      }
      // validating category
      if(empty($_POST['category_id'])) {
        $errors['category_id'] = "a category is required";
      }
      // validating artist
      if(empty($_POST['artist_id'])) {
        $errors['artist_id'] = "an artist is required";
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
          $img_destination = $folder.$_FILES['image']['name'];
          move_uploaded_file($_FILES['image']['tmp_name'], $img_destination);
        }
        else {
          $errors['image'] = "image not valid, allowed types are ".implode(",", $allowed);
        }
      }
    else {
      $errors['image'] = "an image is required";
    }

    //validate audio file
    if(!empty($_FILES['file']['name'])) {
      $folder = "uploads/";
      if(!file_exists($folder)) {
        mkdir($folder,0777,true);
        file_put_contents($folder."index.php", "");
      }

      $allowed = ['audio/mpeg'];
      if($_FILES['file']['error'] == 0 && in_array( $_FILES['file']['type'],$allowed)) {
        $file_destination = $folder.$_FILES['file']['name'];
        move_uploaded_file($_FILES['file']['tmp_name'], $file_destination);
      }
      else {
        $errors['file'] = "audio file not valid, allowed types are ".implode(",", $allowed);
      }
    }
  else {
    $errors['file'] = "an audio file is required";
  }

      if(empty($errors)) {
        $values = [];
        $values['title'] = trim($_POST['title']);
        $values['category_id'] = trim($_POST['category_id']);
        $values['artist_id'] = trim($_POST['artist_id']);
        $values['image'] = $img_destination;
        $values['file'] = $file_destination;
        $values['user_id'] = user('id');
        $values['date'] = date("Y-m-d H:i:s");
        $values['views'] = 0;
        $values['slug'] = str_to_url($values['title']);
      

        $query = "insert into songs(title,image,file,user_id,category_id,artist_id,date,views,slug) values (:title,:image,:file,:user_id,:category_id,:artist_id,:date,:views,:slug)";

        db_query($query,$values);
        message("Song created successfully.");
        redirect('admin/songs');
        
      }
     
    }
 } 
//  edit section
else if($action == 'edit') {
  
  $query = "select * from songs where id = :id limit 1";
  $row = db_query_one($query, ['id'=>$id]);

  if($_SERVER['REQUEST_METHOD'] == "POST" && $row) {
    $errors = [];
    //data validation
    if(empty($_POST['title'])) {
      $errors['title'] = "a title is required";
    }
    else if(!preg_match("/^[a-zA-Z0-9 \.\&\-\+]+$/", $_POST['title'])) {
      $errors['title'] = "enter a valid title";
    }
    // validating category
    if(empty($_POST['category_id'])) {
      $errors['category_id'] = "a category is required";
    }
    // validating artist
    if(empty($_POST['artist_id'])) {
      $errors['artist_id'] = "an artist is required";
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
        $destination_image = $folder.$_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], $destination_image);

        // deleting old image if new one was given in edit
        if(file_exists($row['image'])) {
          unlink($row['image']);
        }
      }
      else {
        $errors['name'] = "image not valid, allowed types are ".implode(",", $allowed);
      }
    }

     //validate audio file
     if(!empty($_FILES['file']['name'])) {
      $folder = "uploads/";
      if(!file_exists($folder)) {
        mkdir($folder,0777,true);
        file_put_contents($folder."index.php", "");
      }

      $allowed = ['audio/mpeg'];
      if($_FILES['file']['error'] == 0 && in_array( $_FILES['file']['type'],$allowed)) {
        $destination_file = $folder.$_FILES['file']['name'];
        move_uploaded_file($_FILES['file']['tmp_name'], $destination_file);

        // deleting old audio
      if(file_exists($row['file'])) {
        unlink($row['file']);
      }
      }
      else {
        $errors['file'] = "audio file not valid, allowed types are ".implode(",", $allowed);
      }
    }
 
    if(empty($errors)) {
      $values = [];
      $values['title'] = trim($_POST['title']);
      $values['category_id'] = trim($_POST['category_id']);
      $values['artist_id'] = trim($_POST['artist_id']);
      $values['user_id'] = user('id');
      $values['id'] = $id;

      $query = "update songs set title = :title,user_id = :user_id,category_id = :category_id,artist_id = :artist_id";
        
      if(!empty($destination_image)) {
        $query .= ", image = :image";
        $values['image'] = $destination_image;
      }

      if(!empty($destination_file)) {
        $query .= ", file = :file";
        $values['file'] = $destination_file;
      }

      $query .= " where id = :id limit 1";
      db_query($query,$values);
      message("Song edited successfully.");
      redirect('admin/songs');
      
    }
   
  }
}

// delete section
else if($action == 'delete') {
  
  $query = "select * from songs where id = :id limit 1";
  $row = db_query_one($query, ['id'=>$id]);

  if($_SERVER['REQUEST_METHOD'] == "POST" && $row) {
    $errors = [];

    if(empty($errors)) {
      $values = [];
      $values['id'] = $id;
      
      $query = "delete from songs where id = :id limit 1";
        
      db_query($query,$values);

      // deleting image
      if(file_exists($row['image'])) {
        unlink($row['image']);
      }
      
      // deleting audio
      if(file_exists($row['file'])) {
        unlink($row['file']);
      }
      
      message("Song deleted successfully.");
      redirect('admin/songs');
    }
   
  }
} 

?>

<?php require page('templates/admin-header')?>

    <section class="admin-content" style="min-height: 200px;">
      
    <!-- add view section -->
      <?php if($action == 'add'):?>
        <div style="max-width: 500px; margin: auto;">
         <form method="post" enctype="multipart/form-data">

          <h3>Add New Song</h3>

          <input class="form-control my-1" value="<?=set_value('title')?>" type="text" name="title" placeholder="Song title">
          <?php if(!empty($errors['title'])): ?>
            <small class="error"><?=$errors['title']?></small>
          <?php endif; ?>

          <!-- selecting category of song -->
          <?php
            $query = "select * from categories order by category asc";
            $categories = db_query($query);
          ?>

          <select name="category_id" class="form-control my-1">
            <option value="">Select Category</option>
            <?php if(!empty($categories)): ?>
              <?php foreach($categories as $cat): ?>
                <option <?=set_select('category_id',$cat['id'])?> value="<?=$cat['id']?>"><?=$cat['category']?></option>
              <?php endforeach; ?>
            <?php endif; ?>
          </select>
          <?php if(!empty($errors['category_id'])): ?>
            <small class="error"><?=$errors['category_id']?></small>
          <?php endif; ?>

          <!-- selecting artist of song -->
          <?php
            $query = "select * from artists order by name asc";
            $artists = db_query($query);
          ?>

          <select name="artist_id" class="form-control my-1">
            <option value="">Select Artist</option>
            <?php if(!empty($artists)): ?>
              <?php foreach($artists as $art): ?>
                <option <?=set_select('artist_id',$art['id'])?> value="<?=$art['id']?>"><?=$art['name']?></option>
              <?php endforeach; ?>
            <?php endif; ?>
          </select>
          <?php if(!empty($errors['artist_id'])): ?>
            <small class="error"><?=$errors['artist_id']?></small>
          <?php endif; ?>

          <!-- image add -->
          <div style="background-color: white;color: #222222;" class="form-control my-1">
            <div>Cover Image:</div>
            <input class="my-1"type="file" name="image">
          </div>
          <!-- image error -->
          <?php if(!empty($errors['image'])): ?>
              <small class="error"><?=$errors['image']?></small>
            <?php endif; ?>

         <!-- audio add -->
         <div style="background-color: white;color: #222222;" class="form-control my-1">
           <div>Audio File:</div>
           <input class="my-1"type="file" name="file">
         </div>
         <!-- audio error -->
         <?php if(!empty($errors['file'])): ?>
             <small class="error"><?=$errors['file']?></small>
         <?php endif; ?>

          <div>
            <button class="btn bg-green my-1">Add</button>
            <a href="<?=ROOT?>/admin/songs">
              <button style="margin-left: 20px;" type="button" class="btn bg-clr my-1">Back</button>
            </a>
          </div>

         </form>
       </div>

       <!-- edit section -->
      <?php elseif($action == 'edit'):?>

        <div style="max-width: 500px; margin: auto;">
         <form method="post"  enctype="multipart/form-data">
          <h3>Edit Song</h3>

        <?php if(!empty($row)):?>
          <input class="form-control my-1" value="<?=set_value('title',$row['title'])?>" type="text" name="title" placeholder="Song title">
          <?php if(!empty($errors['title'])): ?>
            <small class="error"><?=$errors['title']?></small>
          <?php endif; ?>

          <!-- selecting category of song -->
          <?php
            $query = "select * from categories order by category asc";
            $categories = db_query($query);
          ?>

          <select name="category_id" class="form-control my-1">
            <option value="">Select Category</option>
            <?php if(!empty($categories)): ?>
              <?php foreach($categories as $cat): ?>
                <option <?=set_select('category_id',$cat['id'],$row['category_id'])?> value="<?=$cat['id']?>"><?=$cat['category']?></option>
              <?php endforeach; ?>
            <?php endif; ?>
          </select>
          <?php if(!empty($errors['category_id'])): ?>
            <small class="error"><?=$errors['category_id']?></small>
          <?php endif; ?>

          <!-- selecting artist of song -->
          <?php
            $query = "select * from artists order by name asc";
            $artists = db_query($query);
          ?>

          <select name="artist_id" class="form-control my-1">
            <option value="">Select Artist</option>
            <?php if(!empty($artists)): ?>
              <?php foreach($artists as $art): ?>
                <option <?=set_select('artist_id',$art['id'],$row['artist_id'])?> value="<?=$art['id']?>"><?=$art['name']?></option>
              <?php endforeach; ?>
            <?php endif; ?>
          </select>
          <?php if(!empty($errors['artist_id'])): ?>
            <small class="error"><?=$errors['artist_id']?></small>
          <?php endif; ?>

          <!-- image add -->
          <div style="background-color: white;color: #222222;" class="form-control my-1">
            <div>Cover Image:</div>
            <img src="<?=ROOT?>/<?=$row['image']?>" style="margin-top:10px; width: 200px; height: 200px; object-fit:cover;"> 
            <input class="my-1"type="file" name="image">
          </div>
          <!-- image error -->
          <?php if(!empty($errors['image'])): ?>
              <small class="error"><?=$errors['image']?></small>
            <?php endif; ?>

         <!-- audio add -->
         <div style="background-color: white;color: #222222;" class="form-control my-1">
           <div>Audio File:</div>
           <div class="my-1"><?=$row['file']?></div>
           <input class="my-1"type="file" name="file">
         </div>
         <!-- audio error -->
         <?php if(!empty($errors['file'])): ?>
             <small class="error"><?=$errors['file']?></small>
         <?php endif; ?>
    
          <button class="btn bg-clr my-1">Save</button>
          <a href="<?=ROOT?>/admin/songs">
            <button style="margin-left: 20px;" type="button" class="btn bg-clr my-1">Back</button>
          </a>
        <?php else: ?>
          <div class="alert">That record was not found</div>
          <a href="<?=ROOT?>/admin/songs">
            <button type="button" class="btn bg-clr my-1">Back</button>
          </a>
        <?php endif; ?>
         </form>
       </div>

      <!-- delete section -->
      <?php elseif($action == 'delete'):?>
        <div style="max-width: 500px; margin: auto;">
         <form method="post">
          <h3>Delete Song</h3>

        <?php if(!empty($row)):?>

          <div style="background-color: #9F8772; color: #222222;" class="form-control my-1"><?=set_value('title',$row['title'])?></div>

          <?php if(!empty($errors['title'])): ?>
            <small class="error"><?=$errors['title']?></small>
          <?php endif; ?>

          <button class="btn bg-red my-1">Delete</button>
          <a href="<?=ROOT?>/admin/songs">
            <button style="margin-left: 20px;" type="button" class="btn bg-clr my-1">Back</button>
          </a>
        <?php else: ?>
          <div class="alert">That record was not found</div>
          <a href="<?=ROOT?>/admin/songs">
            <button type="button" class="btn bg-clr my-1">Back</button>
          </a>
        <?php endif; ?>
         </form>
       </div>
      <?php else:?>
       <?php 
          $limit = 2;
          $offset = ($page - 1) * $limit;

          $query = "select * from songs order by id desc limit $limit offset $offset";
          $rows = db_query($query);
       ?>
       <h3>Songs
        <a href="<?=ROOT?>/admin/songs/add">
          <button style="margin-left: 20px;" class="btn bg-clr">Add New</button>
        </a>
       </h3>
        <table class="table">
          <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Image</th>
            <th>Category</th>
            <th>Artist</th>
            <th>Audio</th>
            <th>Action</th>
          </tr>

          <?php if(!empty($rows)):?>
            <?php foreach($rows as $row):?>
              <tr>
               <td><?=$row['id']?></td>
               <td><?=$row['title']?></td>
               <td><img src="<?=ROOT?>/<?=$row['image']?>" style="width: 200px; height: 200px; object-fit:cover;"></td>
               <td><?=get_category($row['category_id'])?></td>
               <td><?=get_artist($row['artist_id'])?></td>
               <td>
                  <audio controls>
                    <source src="<?=ROOT?>/<?=$row['file']?>" type="audio/mpeg">
                  </audio>
               </td> 
               <td>
                <a href="<?=ROOT?>/admin/songs/edit/<?=$row['id']?>">
                  <svg width="25" height="25" fill="white" class="bi bi-pencil-square" viewBox="0 0 16 16">
                  <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                  <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                  </svg>
                </a>

                <a href="<?=ROOT?>/admin/songs/delete/<?=$row['id']?>">
                  <svg width="25" height="25" fill="white" class="bi bi-trash3" viewBox="0 0 16 16">
                  <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5ZM11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0H11Zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5h9.916Zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5Z"/>
                  </svg>
                </a>
               </td>
             </tr>
             <?php endforeach; ?>
          <?php endif; ?>
          
        </table>

      <?php endif;?>

    <section class="content" style="padding-bottom: 20px;">
    <a href="<?=ROOT?>/admin/songs?page=<?=$prev_page?>">
       <button class="btn bg-clr">prev</button>
    </a>
    <a href="<?=ROOT?>/admin/songs?page=<?=$next_page?>">
       <button class="float-end btn bg-clr">next</button>
    </a>
    </section>

      
    </section>

<?php require page('templates/admin-footer')?>
    