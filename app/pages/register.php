<?php 
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


    if(empty($errors)) {
      $values = [];
      $values['username'] = trim($_POST['username']);
      $values['email'] = trim($_POST['email']);
      $values['role'] = set_value('role','user');
      $values['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
      $values['date'] = date("Y-m-d H:i:s");

      $query = "insert into users(username,email,password,role,date) values (:username,:email,:password,:role,:date)";

      db_query($query,$values);

      $errors = [];

      $values1['email'] = trim($_POST['email']);
      $query1 = "select * from users where email = :email limit 1";
      $row = db_query_one($query1,$values1);

      if(!empty($row)) {
        if(password_verify($_POST['password'], $row['password'])) {
          authenticate($row);
          message("Account created successfully.");
          redirect('home');
           
        }
    }
   
  }
}

?>


<?php require page('templates/header') ?>
<section class="content">
    <div style="max-width: 500px; margin: auto;">
         <form method="post">
         <?php if(message()):?>
            <div class="alert"><?=message('',true)?></div>
        <?php endif; ?>
          <div class="my-2 pratafont"><p style="color: white; font-size: 30px;">Create new account</p></div>
          <input class="form-control my-1" value="<?=set_value('username')?>" type="text" name="username" placeholder="Username">
          <?php if(!empty($errors['username'])): ?>
            <small class="error"><?=$errors['username']?></small>
          <?php endif; ?>

          <input class="form-control my-1" value="<?=set_value('email')?>" type="email" name="email" placeholder="Email">
          <?php if(!empty($errors['email'])): ?>
            <small class="error"><?=$errors['email']?></small>
          <?php endif; ?>

          <input class="form-control my-1" value="<?=set_value('password')?>" type="password" name="password" placeholder="Password">
          <?php if(!empty($errors['password'])): ?>
            <small class="error"><?=$errors['password']?></small>
          <?php endif; ?>

          <input class="form-control my-1" value="<?=set_value('retype_password')?>" type="password" name="retype_password" placeholder=" Retype Password">
          <button class="btn bg-green my-1">Create</button>
         </form>
  </div>
          </section>

<?php require page('templates/footer') ?>

