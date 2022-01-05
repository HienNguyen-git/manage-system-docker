<?php
    require_once('db.php');
    session_start();
    $user = $_SESSION['user'];
    if (!isset($_SESSION['user'])) {
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
    $post_error = '';
    $success= '';
    $oldpass = '';
    $pass = '';
    $pass_confirm = '';
    
    if (isset($_POST['oldpass']) && isset($_POST['pass']) &&
        isset($_POST['pass-confirm'])) {

        $oldpass = $_POST['oldpass'];
        $pass_md5 = md5($oldpass);
        // echo $pass_md5 . '</br>';
        $pass = $_POST['pass'];
        $pass_confirm = $_POST['pass-confirm'];
        $pass_md5old = select_passmd5($user);
        // echo $pass_md5old;

        if (empty($oldpass)) {
            $post_error = 'Please enter your old password';
        }
        else if($pass_md5 != $pass_md5old){
            $post_error = 'Old password not match';
        }
        else if (empty($pass)) {
            $post_error = 'Please enter your password';
        }
        else if (strlen($pass) < 6) {
            $post_error = 'Password must have at least 6 characters';
        }
        else if ($pass != $pass_confirm) {
            $post_error = 'Confirm Password does not match';
        }
        else {
            $result = change_password($pass,$user);
            $res = employee($user);
            // active_token($_SESSION['user']);
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
                            <div class="form-group">
                                <label for="oldpass">Old Password</label>
                                <input  value="<?= $oldpass?>" name="oldpass" required class="form-control" type="password" placeholder="Old Password" id="oldpass" autofocus>
                                <div class="invalid-feedback">Old Password is not valid.</div>
                            </div>
                            <div class="form-group">
                                <label for="pass">New Password</label>
                                <input  value="<?= $pass?>" name="pass" required class="form-control" type="password" placeholder="Password" id="pass" >
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
