<?php

    if($_SERVER['REQUEST_METHOD'] == "POST") {
      $errors = [];
      $values['email'] = trim($_POST['email']);
      $query = "select * from users where email = :email limit 1";
      $row = db_query_one($query,$values);

      if(!empty($row)) {
        if(password_verify($_POST['password'], $row['password'])) {
          authenticate($row);
          message("login successfull");
          redirect('home');
        }
       
        
      }
      message("wrong email or password");
    }

?>

<?php require page('templates/header') ?>

    <section class="content">
      <div class="login-holder">
      <?php if(message()):?>
       <div class="alert"><?=message('',true)?></div>
      <?php endif; ?>
        <form method="post">
          <center><img class="my-1" style="width: 100px;" src="<?=ROOT?>/assets/images/logo2.png"></center>
          <p class="pratafont" style="padding-bottom: 15px; text-align: center;font-size: 30px;">Login</p>
          <input value="<?=set_value('email')?>" class="form-control my-1" type="email" name="email" placeholder="Email">
          <input value="<?=set_value('password')?>" class="form-control" type="password" name="password" placeholder="Password">
          <button class="my-1 btn bg-clr">Login</button>
        </form>
      </div>
    </section>

<?php require page('templates/footer') ?>