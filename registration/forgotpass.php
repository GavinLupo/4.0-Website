<?php include('server.php') ?>
<!DOCTYPE html>
<html>
<head>
  <title>Forgot Password system PHP and MySQL</title>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <img src="images/ta.png" alt="TA logo">
  <div class="header">
  	<h2>Forgot Password</h2>
  </div>

  <form method="post" action="forgotpass.php">
   
  	<div class="input-group">
  	  <label>Email</label>
  	  <input type="email" name="email" class="form-control" value="<?php echo $email; ?>">
      
  	</div>
  	<div class="input-group">

  	  <label>New password</label>
  	  <input type="password" name="pass" class="form-control">
  	</div>
  	<div class="input-group">
        
  	  <label>Confirm new password</label>
  	  <input type="password" name="cpass" class="form-control">
  	</div>
  	<div class="input-group">
  	  <button type="submit"  class="btn" name="submit" value="Change Password" >Submit</button>
  	</div>
    <p>
  		<a href="login.php">Log in</a>
  	</p>
  </form>
</body>
</html>

