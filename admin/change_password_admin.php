<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
        <title>Admin Login</title>
        <link rel="icon" type="image/x-icon" href="../Resource/Admin_Panel_Icon.avif"/>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous"/>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"/>
        <link rel="preconnect" href="https://fonts.googleapis.com"/>
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
        <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed&display=swap" rel="stylesheet"/>
        <link rel="stylesheet" type="text/css" href="../CSS/index.css">
        <link rel="stylesheet" type="text/css" href="../CSS/admin_login.css">
    </head>
        
    <body>

<section class="mt-5">
    <form class="mx-auto" method="POST" action="./admin-auth.php?action=changePd">
        <h3 class="text-center mx-auto mb-4 loginHeader"> SIGN IN TO YOUR ACCOUNT</h3>
        <div class="form-outline text-center  mb-4 mx-auto">  
            <input class="loginInput" id="username" name="username" placeholder="User name" required />
        </div> 
        <div class="form-outline text-center  mb-4 mx-auto"> 
          <input class="loginInput" type="password" id="old_password" name="old_password" placeholder="Old password" required/>
        </div>
        <div class="form-outline text-center  mb-4 mx-auto"> 
          <input class="loginInput" type="password" id="new_password_1" name="new_password_1" placeholder="New password" required/>
        </div>
        <div class="form-outline text-center  mb-4 mx-auto"> 
          <input class="loginInput" type="password" id="new_password_2" name="new_password_2" placeholder="Re-enter new password" required/>
        </div>
        
        <!-- CSRF -->
        <input type="hidden" name="nonce" value=<?php csrf_getNonce("changePd") ?>/>

        <div class="form-outline text-center mb-4 mx-auto">
          <button class="btn btn-warning btn-block loginInput" > Submit </button>
        </div>
        </form>
  </section>

</body>