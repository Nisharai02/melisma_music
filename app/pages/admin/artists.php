<?php

 if($action == 'add') {

    if($_SERVER['REQUEST_METHOD'] == "POST") {
      $errors = [];
      //data validation
      if(empty($_POST['name'])) {
        $errors['name'] = "a name is required";
      }
      else if(!preg_match("/^[a-zA-Z \&\-]+$/", $_POST['name'])) {
        $errors['name'] = "enter a valid name";
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
        }
        else {
          $errors['name'] = "image not valid, allowed types are ".implode(",", $allowed);
        }
      }
    else {
      $errors['name'] = "an image is required";
    }

      if(empty($errors)) {
        $values = [];
        $values['name'] = trim($_POST['name']);
        $values['image'] = $destination;
        $values['user_id'] = user('id');
        $values['bio'] = trim($_POST['bio']);

        $query = "insert into artists(name,image,user_id,bio) values (:name,:image,:user_id,:bio)";

        db_query($query,$values);
        message("Artist created successfully.");
        redirect('admin/artists');
        
      }
     
    }
 } 
//  edit section
else if($action == 'edit') {
  
  $query = "select * from artists where id = :id limit 1";
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
      $values['bio'] = trim($_POST['bio']);
      $values['id'] = $id;
      $query = "update artists set name = :name,bio = :bio,user_id = :user_id where id = :id limit 1";
        
      if(!empty($destination)) {
        $query = "update artists set name = :name,bio = :bio,user_id = :user_id, image = :image where id = :id limit 1";
        $values['image'] = $destination;
      }
        
      db_query($query,$values);
      message("Artist edited successfully.");
      redirect('admin/artists');
      
    }
   
  }
}

// delete section
else if($action == 'delete') {
  
  $query = "select * from artists where id = :id limit 1";
  $row = db_query_one($query, ['id'=>$id]);

  if($_SERVER['REQUEST_METHOD'] == "POST" && $row) {
    $errors = [];

    if(empty($errors)) {
      $values = [];
      $values['id'] = $id;
      
      $query = "delete from artists where id = :id limit 1";
        
      db_query($query,$values);

      // deleting old image if new one was given in edit
      if(file_exists($row['image'])) {
        unlink($row['image']);
      }
      
      message("Artist deleted successfully.");
      redirect('admin/artists');
    }
   
  }
} 

?>

<?php require page('templates/admin-header')?>

    <section class="admin-content" style="min-height: 200px;">
      
    <!-- add section -->
      <?php if($action == 'add'):?>
        <div style="max-width: 500px; margin: auto;">
         <form method="post" enctype="multipart/form-data">

          <h3>Add New Artist</h3>
          <input class="form-control my-1" value="<?=set_value('name')?>" type="text" name="name" placeholder="Artist name">
          <?php if(!empty($errors['name'])): ?>
            <small class="error"><?=$errors['name']?></small>
          <?php endif; ?>
         
          <div style="padding-top:15px;">Artist Image</div>
         <input class="form-control my-1" type="file" name="image">
          <?php if(!empty($errors['image'])): ?>
            <small class="error"><?=$errors['image']?></small>
          <?php endif; ?>

          <label>Artist Bio</label>
          <textarea rows="10" name="bio" class="form-control my-1">
             <?=set_value('bio')?>
          </textarea>

          <button class="btn bg-green my-1">Add</button>
          <a href="<?=ROOT?>/admin/artists">
            <button style="margin-left: 20px;" type="button" class="btn bg-clr my-1">Back</button>
          </a>
         </form>
       </div>

       <!-- edit section -->
      <?php elseif($action == 'edit'):?>

        <div style="max-width: 500px; margin: auto;">
         <form method="post"  enctype="multipart/form-data">
          <h3>Edit Artist</h3>

        <?php if(!empty($row)):?>
          <input class="form-control my-1" value="<?=set_value('name',$row['name'])?>" type="text" name="name" placeholder="Artistname">
          <?php if(!empty($errors['name'])): ?>
            <small class="error"><?=$errors['name']?></small>
          <?php endif; ?>

          <img src="<?=ROOT?>/<?=$row['image']?>" style="width: 200px; height: 200px; object-fit:cover;">
          
          <div style="padding-top:15px;">Artist Image</div>
          <input class="form-control my-1" type="file" name="image">
          
          <label>Artist Bio</label>
          <textarea rows="10" name="bio" class="form-control my-1">
             <?=set_value('bio',$row['bio'])?>
          </textarea>

          <button class="btn bg-clr my-1">Save</button>
          <a href="<?=ROOT?>/admin/artists">
            <button style="margin-left: 20px;" type="button" class="btn bg-clr my-1">Back</button>
          </a>
        <?php else: ?>
          <div class="alert">That record was not found</div>
          <a href="<?=ROOT?>/admin/artists">
            <button type="button" class="btn bg-clr my-1">Back</button>
          </a>
        <?php endif; ?>
         </form>
       </div>

      <!-- delete section -->
      <?php elseif($action == 'delete'):?>
        <div style="max-width: 500px; margin: auto;">
         <form method="post">
          <h3>Delete Artist</h3>

        <?php if(!empty($row)):?>

          <div style="background-color: #9F8772; color: #222222;" class="form-control my-1"><?=set_value('name',$row['name'])?></div>

          <?php if(!empty($errors['name'])): ?>
            <small class="error"><?=$errors['name']?></small>
          <?php endif; ?>

          <button class="btn bg-red my-1">Delete</button>
          <a href="<?=ROOT?>/admin/artists">
            <button style="margin-left: 20px;" type="button" class="btn bg-clr my-1">Back</button>
          </a>
        <?php else: ?>
          <div class="alert">That record was not found</div>
          <a href="<?=ROOT?>/admin/artists">
            <button type="button" class="btn bg-clr my-1">Back</button>
          </a>
        <?php endif; ?>
         </form>
       </div>
      <?php else:?>
       <?php  
          $query = "select * from artists order by id desc limit 20";
          $rows = db_query($query);
       ?>
       <h3>Artists
        <a href="<?=ROOT?>/admin/artists/add">
          <button style="margin-left: 20px;" class="btn bg-clr">Add New</button>
        </a>
       </h3>
        <table class="table">
          <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Artist</th>
            <th>Action</th>
          </tr>

          <?php if(!empty($rows)):?>
            <?php foreach($rows as $row):?>
              <tr>
               <td><?=$row['id']?></td>
               <td><?=$row['name']?></td>

               <td><a href="<?=ROOT?>/artist/<?=$row['id']?>"><img src="<?=ROOT?>/<?=$row['image']?>" style="width: 200px; height: 200px; object-fit:cover;"></a></td>

               <td>
                <a href="<?=ROOT?>/admin/artists/edit/<?=$row['id']?>">
                  <svg width="25" height="25" fill="white" class="bi bi-pencil-square" viewBox="0 0 16 16">
                  <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                  <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                  </svg>
                </a>

                <a href="<?=ROOT?>/admin/artists/delete/<?=$row['id']?>">
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

      
    </section>

<?php require page('templates/admin-footer')?>
    