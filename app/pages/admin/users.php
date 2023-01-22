<?php

 if($action == 'add') {

    if($_SERVER['REQUEST_METHOD'] == "POST") {
      $errors = [];
      //data validation
      if(empty($_POST['username'])) {
        $errors['username'] = "a username is required";
      }
      else if(!preg_match("/^[a-zA-Z]+$/", $_POST['username'])) {
        $errors['username'] = "username can only have letters with no spaces";
      }

      if(empty($_POST['email'])) {
        $errors['email'] = "an email is required";
      }
      else if(!filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "invalid email";
      }

      if(empty($_POST['password'])) {
        $errors['password'] = "a password is required";
      }
      else if($_POST['password'] != $_POST['retype_password']) {
        $errors['password'] = "passwords do not match";
      }
      else if(strlen($_POST['password']) < 8) {
        $errors['password'] = "passwords must be atleast 8 characters";
      }

      if(empty($_POST['role'])) {
        $errors['role'] = "a role is required";
      }

      if(empty($errors)) {
        $values = [];
        $values['username'] = trim($_POST['username']);
        $values['email'] = trim($_POST['email']);
        $values['role'] = trim($_POST['role']);
        $values['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $values['date'] = date("Y-m-d H:i:s");

        $query = "insert into users(username,email,password,role,date) values (:username,:email,:password,:role,:date)";

        db_query($query,$values);
        message("user created successfully.");
        redirect('admin/users');
        
      }
     
    }
 } 
else if($action == 'edit') {
  
  $query = "select * from users where id = :id limit 1";
  $row = db_query_one($query, ['id'=>$id]);

  if($_SERVER['REQUEST_METHOD'] == "POST" && $row) {
    $errors = [];
    //data validation
    if(empty($_POST['username'])) {
      $errors['username'] = "a username is required";
    }
    else if(!preg_match("/^[a-zA-z]+$/", $_POST['username'])) {
      $errors['username'] = "username can only have letters with no spaces";
    }

    if(empty($_POST['email'])) {
      $errors['email'] = "an email is required";
    }
    else if(!filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)) {
      $errors['email'] = "invalid email";
    }

    if(!empty($_POST['password'])) {
      if($_POST['password'] != $_POST['retype_password']) {
        $errors['password'] = "passwords do not match";
      }
      else if(strlen($_POST['password']) < 8) {
        $errors['password'] = "passwords must be atleast 8 characters";
      }
    }

    if(empty($_POST['role'])) {
      $errors['role'] = "a role is required";
    }

    if(empty($errors)) {
      $values = [];
      $values['username'] = trim($_POST['username']);
      $values['email'] = trim($_POST['email']);
      $values['role'] = trim($_POST['role']);
      $values['id'] = $id;
      
      $query = "update users set email = :email, username = :username, role = :role where id = :id limit 1";

      if(!empty($_POST['password'])) {
        $query = "update users set email = :email, password = :password, username = :username, role = :role where id = :id limit 1";
        $values['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
      }
        
      db_query($query,$values);
      message("user edited successfully.");
      redirect('admin/users');
      
    }
   
  }
}
else if($action == 'delete') {
  
  $query = "select * from users where id = :id limit 1";
  $row = db_query_one($query, ['id'=>$id]);

  if($_SERVER['REQUEST_METHOD'] == "POST" && $row) {
    $errors = [];
    
    // cannot delete main admin
    if($row['id'] == 1) {
      $errors['username'] = "the main admin cannot be deleted";
    }

    if(empty($errors)) {
      $values = [];
      $values['id'] = $id;
      
      $query = "delete from users where id = :id limit 1";
        
      db_query($query,$values);
      message("user deleted successfully.");
      redirect('admin/users');
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
          <h3>Add New User</h3>
          <input class="form-control my-1" value="<?=set_value('username')?>" type="text" name="username" placeholder="Username">
          <?php if(!empty($errors['username'])): ?>
            <small class="error"><?=$errors['username']?></small>
          <?php endif; ?>

          <input class="form-control my-1" value="<?=set_value('email')?>" type="email" name="email" placeholder="Email">
          <?php if(!empty($errors['email'])): ?>
            <small class="error"><?=$errors['email']?></small>
          <?php endif; ?>

          <!-- assigning role -->
          <select name="role" class="form-control my-1">
            <option value="">Select Role</option>
            <option <?=set_select('role','user')?> value="user">User</option>
            <option <?=set_select('role','admin')?> value="admin">Admin</option>
          </select>
          <?php if(!empty($errors['role'])): ?>
            <small class="error"><?=$errors['role']?></small>
          <?php endif; ?>

          <input class="form-control my-1" value="<?=set_value('password')?>" type="password" name="password" placeholder="Password">
          <?php if(!empty($errors['password'])): ?>
            <small class="error"><?=$errors['password']?></small>
          <?php endif; ?>

          <input class="form-control my-1" value="<?=set_value('retype_password')?>" type="password" name="retype_password" placeholder=" Retype Password">
          <button class="btn bg-green my-1">Add</button>
          <a href="<?=ROOT?>/admin/users">
            <button style="margin-left: 20px;" type="button" class="btn bg-clr my-1">Back</button>
          </a>
         </form>
       </div>

       <!-- edit section -->
      <?php elseif($action == 'edit'):?>

        <div style="max-width: 500px; margin: auto;">
         <form method="post">
          <h3>Edit User</h3>

        <?php if(!empty($row)):?>
          <input class="form-control my-1" value="<?=set_value('username',$row['username'])?>" type="text" name="username" placeholder="Username">
          <?php if(!empty($errors['username'])): ?>
            <small class="error"><?=$errors['username']?></small>
          <?php endif; ?>

          <input class="form-control my-1" value="<?=set_value('email',$row['email'])?>" type="email" name="email" placeholder="Email">
          <?php if(!empty($errors['email'])): ?>
            <small class="error"><?=$errors['email']?></small>
          <?php endif; ?>

          <select name="role" class="form-control my-1">
            <option value="">Select Role</option>
            <option <?=set_select('role','user',$row['role'])?> value="user">User</option>
            <option <?=set_select('role','admin',$row['role'])?> value="admin">Admin</option>
          </select>
          <?php if(!empty($errors['role'])): ?>
            <small class="error"><?=$errors['role']?></small>
          <?php endif; ?>

          <input class="form-control my-1" value="<?=set_value('password')?>" type="password" name="password" placeholder="Password (leave empty to keep old one)">
          <?php if(!empty($errors['password'])): ?>
            <small class="error"><?=$errors['password']?></small>
          <?php endif; ?>

          <input class="form-control my-1" value="<?=set_value('retype_password')?>" type="password" name="retype_password" placeholder=" Retype Password">
          <button class="btn bg-clr my-1">Save</button>
          <a href="<?=ROOT?>/admin/users">
            <button style="margin-left: 20px;" type="button" class="btn bg-clr my-1">Back</button>
          </a>
        <?php else: ?>
          <div class="alert">That record was not found</div>
          <a href="<?=ROOT?>/admin/users">
            <button type="button" class="btn bg-clr my-1">Back</button>
          </a>
        <?php endif; ?>
         </form>
       </div>

      <!-- delete section -->
      <?php elseif($action == 'delete'):?>
        <div style="max-width: 500px; margin: auto;">
         <form method="post">
          <h3>Delete User</h3>

        <?php if(!empty($row)):?>

          <div style="background-color: #9F8772; color: #222222;" class="form-control my-1"><?=set_value('username',$row['username'])?></div>

          <?php if(!empty($errors['username'])): ?>
            <small class="error"><?=$errors['username']?></small>
          <?php endif; ?>
          
          <div style="background-color: #9F8772; color: #222222;" class="form-control my-1"><?=set_value('email',$row['email'])?></div>
          <div style="background-color: #9F8772; color: #222222;" class="form-control my-1"><?=set_value('role',$row['role'])?></div>

          <button class="btn bg-red my-1">Delete</button>
          <a href="<?=ROOT?>/admin/users">
            <button style="margin-left: 20px;" type="button" class="btn bg-clr my-1">Back</button>
          </a>
        <?php else: ?>
          <div class="alert">That record was not found</div>
          <a href="<?=ROOT?>/admin/users">
            <button type="button" class="btn bg-clr my-1">Back</button>
          </a>
        <?php endif; ?>
         </form>
       </div>
      <?php else:?>
       <?php  
          $query = "select * from users order by id desc limit 20";
          $rows = db_query($query);
       ?>
       <h3>Users
        <a href="<?=ROOT?>/admin/users/add">
          <button style="margin-left: 20px;" class="btn bg-clr">Add New</button>
        </a>
       </h3>
        <table class="table">
          <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Date</th>
            <th>Action</th>
          </tr>

          <?php if(!empty($rows)):?>
            <?php foreach($rows as $row):?>
              <tr>
               <td><?=$row['id']?></td>
               <td><?=$row['username']?></td>
               <td><?=$row['email']?></td>
               <td><?=$row['role']?></td>
               <td><?=get_date($row['date'])?></td>
               <td>
                <a href="<?=ROOT?>/admin/users/edit/<?=$row['id']?>">
                  <svg width="25" height="25" fill="white" class="bi bi-pencil-square" viewBox="0 0 16 16">
                  <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                  <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                  </svg>
                </a>

                <a href="<?=ROOT?>/admin/users/delete/<?=$row['id']?>">
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
    