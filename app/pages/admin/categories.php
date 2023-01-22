<?php

 if($action == 'add') {

    if($_SERVER['REQUEST_METHOD'] == "POST") {
      $errors = [];
      //data validation
      if(empty($_POST['category'])) {
        $errors['category'] = "a category is required";
      }
      else if(!preg_match("/^[a-zA-Z \&\-]+$/", $_POST['category'])) {
        $errors['category'] = "enter a valid category name";
      }

      if(empty($errors)) {
        $values = [];
        $values['category'] = trim($_POST['category']);
        $values['disabled'] = trim($_POST['disabled']);

        $query = "insert into categories(category,disabled) values (:category,:disabled)";

        db_query($query,$values);
        message("Category created successfully.");
        redirect('admin/categories');
        
      }
     
    }
 } 
else if($action == 'edit') {
  
  $query = "select * from categories where id = :id limit 1";
  $row = db_query_one($query, ['id'=>$id]);

  if($_SERVER['REQUEST_METHOD'] == "POST" && $row) {
    $errors = [];
    //data validation
    if(empty($_POST['category'])) {
      $errors['category'] = "a category is required";
    }
    else if(!preg_match("/^[a-zA-Z \&\-]+$/", $_POST['category'])) {
      $errors['category'] = "enter a valid category name";
    }

    if(empty($errors)) {
      $values = [];
      $values['category'] = trim($_POST['category']);
      $values['disabled'] = trim($_POST['disabled']);
      $values['id'] = $id;
      
      $query = "update categories set category = :category, disabled = :disabled where id = :id limit 1";
        
      db_query($query,$values);
      message("Category edited successfully.");
      redirect('admin/categories');
      
    }
   
  }
}
else if($action == 'delete') {
  
  $query = "select * from categories where id = :id limit 1";
  $row = db_query_one($query, ['id'=>$id]);

  if($_SERVER['REQUEST_METHOD'] == "POST" && $row) {
    $errors = [];

    if(empty($errors)) {
      $values = [];
      $values['id'] = $id;
      
      $query = "delete from categories where id = :id limit 1";
        
      db_query($query,$values);
      message("Category deleted successfully.");
      redirect('admin/categories');
    }
   
  }
} 

?>

<?php require page('templates/admin-header')?>

    <section class="admin-content" style="min-height: 200px;">
      
    <!-- add section -->
      <?php if($action == 'add'):?>
        <div style="max-width: 500px; margin: auto;">
         <form method="post">
          <h3>Add New Category</h3>
          <input class="form-control my-1" value="<?=set_value('category')?>" type="text" name="category" placeholder="Category name">
          <?php if(!empty($errors['category'])): ?>
            <small class="error"><?=$errors['category']?></small>
          <?php endif; ?>

          <select name="disabled" class="form-control my-1">
            <option value="">Select Status</option>
            <option <?=set_select('disabled','1')?> value="1">Disabled</option>
            <option <?=set_select('disabled','0')?> value="0">Active</option>
          </select>
          <?php if(!empty($errors['disabled'])): ?>
            <small class="error"><?=$errors['disabled']?></small>
          <?php endif; ?>

          <button class="btn bg-green my-1">Add</button>
          <a href="<?=ROOT?>/admin/categories">
            <button style="margin-left: 20px;" type="button" class="btn bg-clr my-1">Back</button>
          </a>
         </form>
       </div>

       <!-- edit section -->
      <?php elseif($action == 'edit'):?>

        <div style="max-width: 500px; margin: auto;">
         <form method="post">
          <h3>Edit Category</h3>

        <?php if(!empty($row)):?>
          <input class="form-control my-1" value="<?=set_value('category',$row['category'])?>" type="text" name="category" placeholder="Categoryname">
          <?php if(!empty($errors['category'])): ?>
            <small class="error"><?=$errors['category']?></small>
          <?php endif; ?>
          
          <select name="disabled" class="form-control my-1">
            <option value="">Select Status</option>
            <option <?=set_select('disabled','1',$row['disabled'])?> value="1">Disabled</option>
            <option <?=set_select('disabled','0',$row['disabled'])?> value="0">Active</option>
          </select>

          <button class="btn bg-clr my-1">Save</button>
          <a href="<?=ROOT?>/admin/categories">
            <button style="margin-left: 20px;" type="button" class="btn bg-clr my-1">Back</button>
          </a>
        <?php else: ?>
          <div class="alert">That record was not found</div>
          <a href="<?=ROOT?>/admin/categories">
            <button type="button" class="btn bg-clr my-1">Back</button>
          </a>
        <?php endif; ?>
         </form>
       </div>

      <!-- delete section -->
      <?php elseif($action == 'delete'):?>
        <div style="max-width: 500px; margin: auto;">
         <form method="post">
          <h3>Delete Category</h3>

        <?php if(!empty($row)):?>

          <div style="background-color: #9F8772; color: #222222;" class="form-control my-1"><?=set_value('category',$row['category'])?></div>

          <?php if(!empty($errors['category'])): ?>
            <small class="error"><?=$errors['category']?></small>
          <?php endif; ?>

          <button class="btn bg-red my-1">Delete</button>
          <a href="<?=ROOT?>/admin/categories">
            <button style="margin-left: 20px;" type="button" class="btn bg-clr my-1">Back</button>
          </a>
        <?php else: ?>
          <div class="alert">That record was not found</div>
          <a href="<?=ROOT?>/admin/categories">
            <button type="button" class="btn bg-clr my-1">Back</button>
          </a>
        <?php endif; ?>
         </form>
       </div>
      <?php else:?>
       <?php  
          $query = "select * from categories order by id desc limit 20";
          $rows = db_query($query);
       ?>
       <h3>Categories
        <a href="<?=ROOT?>/admin/categories/add">
          <button style="margin-left: 20px;" class="btn bg-clr">Add New</button>
        </a>
       </h3>
        <table class="table">
          <tr>
            <th>ID</th>
            <th>Category</th>
            <th>Active</th>
            <th>Action</th>
          </tr>

          <?php if(!empty($rows)):?>
            <?php foreach($rows as $row):?>
              <tr>
               <td><?=$row['id']?></td>
               <td><?=$row['category']?></td>
               <td><?=$row['disabled'] ? 'No' : 'Yes'?></td>
               <td>
                <a href="<?=ROOT?>/admin/categories/edit/<?=$row['id']?>">
                  <svg width="25" height="25" fill="white" class="bi bi-pencil-square" viewBox="0 0 16 16">
                  <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                  <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                  </svg>
                </a>

                <a href="<?=ROOT?>/admin/categories/delete/<?=$row['id']?>">
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
    