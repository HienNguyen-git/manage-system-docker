<?php
    session_start();
    ob_start();
    require_once('db.php');
   if(!isset($_SESSION['user'])){
       header('Location: /login.php');
   }
   $user = $_SESSION['user'];
   if( !is_password_changed($user) ){
       header('Location: /change_password.php');
       exit();
   }else if(get_info_employee_byuser($_SESSION['user'])['role'] != 'employee' ){
    move_page(get_info_employee_byuser($_SESSION['user'])['role']);
    exit();
}


   $error = '';
   $message = "";
   
    if (isset($_FILES['file'])) {
        $file = $_FILES['file'];
        $errors= array();
        $file_name = $file['name'];
        $file_size =$file['size'];
        $file_tmp =$file['tmp_name'];
        $file_type=$file['type'];
        $file_extend=explode('.',$file_name);
        $file_ext = strtolower(end($file_extend));
        $extensions= array("jpg","png","jpeg","gif");
        if(empty($errors)){
            $file_path = "images/".$file_name;
            move_uploaded_file($file_tmp, $file_path);
            $message = "Submit successful";
            update_avatar($user,$file_path);
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css"
        integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="/style.css"> <!-- Change -->
	<title>Account Page</title>
</head>

    <body>
	<input type="hidden" name="page" id="page" value="account">

    <?php
        include_once('layout/header.php');
    ?>
    <section class="container" style="height: 70vh;">
		<h1 class="mt-3 text-secondary">ACCOUNT INFORMATION</h1>
        <h3 class="mt-1 mb-3 pb-3 border-bottom border-info text-light"><?=$user?></h3>
        <a class="btn btn-primary col-12 col-sm-4" href="change_pass.php">Change password</a>
        <div class="ml-auto mr-auto account-container" style="height: 50vh;">
            <?php
                $data = get_user_info($user);
                if(!$data['code']){
                    $row = $data['data'];
                    ?>
                    <div class="image-box">
                        <img src="<?=$row['avatar']?>" alt="Avatar">
                        <div class="image-action">
                            <button style="display: block; background-color:transparent; border: 0; color: #fff" data-toggle="modal" data-target="#edit-avatar"><i class="fas fa-images"></i> Change image</button>
                        </div>
                    </div>
                    <table>
                    <tr>
                        <th>ID:</th>
                        <td><?=$row['id']?></td>
                    </tr>
                    <tr>
                        <th>Username:</th>
                        <td><?=$user?></td>
                    </tr>
                    <tr>
                        <th>Full name:</th>
                        <td><?=$row['firstname']?> <?=$row['lastname']?></td>
                    </tr>
                    <tr>
                        <th>Role:</th>
                        <td><?=$row['role']?></td>
                    </tr>
                    <tr>
                        <th>Department:</th>
                        <td><?=$row['department']?></td>
                    </tr>
                    <?php
                }
            ?>
            
        </table>
        </div>
    </section>

    <div id="edit-avatar" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <hp class="modal-title">Upload new image</hp>
                    <button type="button" class="close" data-dismiss="modal" >&times;</button>
                </div>
                <div class="modal-body">
                    <form method="POST" enctype="multipart/form-data"> 
                        <div class="form-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="file" id="file">
                                <label class="custom-file-label" for="file">Choose image</label>
                            </div>
                        </div>
                        <div class="form-group" id="error-message">
                        </div>
                            <div class="form-group">
                            <button type="submit" id="upload-btn" class="btn btn-primary col-12 col-sm-12`">Submit</button>
                        </div>
                    </form>
                </div>
            </div>  
        </div>
    </div>
<script src="/main.js"></script> 
</body>
</html>
<?php
    ob_end_flush();
?>