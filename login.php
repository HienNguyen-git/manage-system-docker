<?php
    session_start();
    require_once('db.php');
    
    if (isset($_SESSION['user'])) {
        $role = get_info_employee_byuser($_SESSION['user'])['role'];
        move_page($role);
        exit();
    }

    $error = '';
    $role ='';
    $user = '';
    $pass = '';

    if (isset($_POST['user']) && isset($_POST['pass'])) {
        $user = $_POST['user'];
        $pass = $_POST['pass'];

        if (empty($user)) {
            $error = 'Please enter your username';
        }
        else if (empty($pass)) {
            $error = 'Please enter your password';
        }
        else if (strlen($pass) < 6) {
            $error = 'Password must have at least 6 characters';
        }
        else{
            $result= login($user,$pass);
            if($result['code'] == 0){
                $data = $result['data'];
                $_SESSION['user'] = $user;
                $_SESSION['name'] = $data['firstname'] . ' ' . $data['lastname'];
                
                move_page($data['role']);
                exit();
            }
            else { 
                $error = $result['error'];
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>User Login</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body style="background-color: #ccc;">
<div class="container">
    <div class="row justify-content-center ">
        <div class="col-md-6 col-lg-5">
            <h3 class="text-center text-secondary mt-5 mb-3">User Login</h3>
            <form method="post" action="" class="bg-info border rounded w-100 mb-5 mx-auto px-3 pt-3 ">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input value="<?= $user ?>" name="user" id="user" type="text" class="form-control" placeholder="Username">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input name="pass" value="<?= $pass ?>" id="password" type="password" class="form-control" placeholder="Password">
                </div>
                <!--  -->
                <div class="form-group">
                    <?php
                        if (!empty($error)) {
                            echo "<div class='alert alert-danger'>$error</div>";
                        }
                    ?>
                    <button class="btn btn-success px-5" style="width: 100%;">Login</button>
                </div>
               
                   
                    <!-- <p>Forgot your password? <a href="db.php">Reset your password</a>.</p> -->
           
                
            </form>
            
        </div>
    </div>
</div>

</body>
</html>
