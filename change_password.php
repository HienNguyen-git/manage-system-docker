<?php
    ob_start();
     
    session_start();
    require_once('db.php');
    $user = $_SESSION['user'];
    if (!isset($_SESSION['user'])) {
        header('Location: login.php');
        exit();
    }
	$user = $_SESSION['user'];
	// echo is_password_changed($user);
	if( is_password_changed($user) ){
		// echo "pass changed";
		header('Location: login.php');
		exit();
	}
?>
<DOCTYPE html>
<html lang="en">
<head>
    <title>Reset user password</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body style="background-color: #ccc;">
<?php
    $error = '';
    $pass = '';
    $pass_confirm = '';
    $post_error = '';
    $success= '';
    
            if (isset($_POST['pass']) &&
                isset($_POST['pass-confirm'])) {

                // $email = $_POST['email'];
                $pass = $_POST['pass'];
                $pass_confirm = $_POST['pass-confirm'];

                // if (empty($email)) {
                //     $post_error = 'Please enter your email';
                // }
                // else if (filter_var($email, FILTER_VALIDATE_EMAIL) == false) {
                //     $post_error = 'This is not a valid email address';
                // }
                if (empty($pass)) {
                    $post_error = 'Please enter your password';
                }
                else if (strlen($pass) < 6) {
                    $post_error = 'Password must have at least 6 characters';
                }
                else if ($pass != $pass_confirm) {
                    $post_error = 'Password does not match';
                }
                else {
                    // echo 'Good';
                    $result = change_password($pass,$user);
                    $res = employee($user);
                    active_token($_SESSION['user']);
                    if($result['code'] == 0){
                        
                        $success = $result['success'];
                        $data = $res['data'];
                        
                        if($data['role'] == 'employee'){
                            header('Location: index.php');
                        }
                        else if($data['role'] == 'manager'){
                            header('Location: manager/index.php');
                        }
                        else{
                            header('Location: admin/index.php');
                        }
                        exit();
                    }else{
                        $post_error = $result['error'];
                    }
                }
            }
            else {
                // print_r($_POST);
                // $error = 'Something went wrong';
            }
        // }
    // }else{
    //     $error = 'Invalid email or token';
    // }

    
?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <h3 class="text-center text-secondary mt-5 mb-3">Change Password</h3>
            <?php 
                if(!empty($error)){
                    echo "<div class='alert alert-danger'>$error</div>";
                        
                }else{
                    ?>
                        <form novalidate method="post" action="" class="border rounded w-100 mb-5 mx-auto px-3 pt-3 bg-info">
                            <!-- <div class="form-group">
                                <label for="email">Email</label> -->
                                <!-- <input readonly value="sample@gmail.com" name="email" id="email" type="text" class="form-control" placeholder="Email address"> -->
                                <!-- <input readonly value="<?= $display_email ?>" name="email" id="email" type="text" class="form-control" placeholder="Email address">
                            </div> -->
                            <div class="form-group">
                                <label for="pass">Password</label>
                                <input  value="<?= $pass?>" name="pass" required class="form-control" type="password" placeholder="Password" id="pass" autofocus>
                                <div class="invalid-feedback">Password is not valid.</div>
                            </div>
                            <div class="form-group">
                                <label for="pass2">Confirm Password</label>
                                <input value="<?= $pass_confirm?>" name="pass-confirm" required class="form-control" type="password" placeholder="Confirm Password" id="pass2">
                                <div class="invalid-feedback">Password is not valid.</div>
                            </div>
                            <div class="form-group">
                                <?php 
                                if(!empty($post_error)){
                                    echo "<div class='alert alert-danger'>$post_error</div>";
                                        
                                }
                                if(!empty($success)){
                                    echo "<div class='alert alert-success'>$success</div>";
                                        
                                }
                                ?>
                                <button class="btn btn-success px-5">Change password</button>
                            </div>
                        </form>
                    <?php
                }
                
                ?>
            
            
        </div>
    </div>
</div>

</body>
</html>
<?php 

    ob_end_flush();
?>
