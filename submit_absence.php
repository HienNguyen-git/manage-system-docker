<?php
    session_start();
    ob_start();
    require_once('db.php');
    if(!isset($_SESSION['user'])){
        header('Location: login.php');
    }
    $user = $_SESSION['user'];
    if( !is_password_changed($user) ){
		header('Location: change_password.php');
		exit();
	}

    if(!isset($_POST['description'])){
        http_response_code(400);
        die(json_encode(array('code'=>1,'message'=>'Please enter your description ')));
    }else if(!isset($_POST['dayoff'])){
        http_response_code(400);
        die(json_encode(array('code'=>2,'message'=>'Please enter your dayoff ')));
    }else if(!isset($_FILES['file'])){
        http_response_code(200);
        submit_absence_form($user,$_POST['dayoff'],$_POST['description'],'-');
        die(json_encode(array('code'=>0,'message'=>'Submit successful')));
    }
    else{
        $description = $_POST['description'];
        $file = $_FILES['file'];
        $dayoff = $_POST['dayoff'];

        $errors= array();
        $file_name = $file['name'];
        $file_size =$file['size'];
        $file_tmp =$file['tmp_name'];
        $file_ext=strtolower(end(explode('.',$file_name)));

        $extensions= array("txt","doc","docx","xls","xlsx","jpg","png","mp3","mp4","pdf","rar","zip","pptx","html","sql","ppt","jpeg");
        
        if(!in_array($file_ext,$extensions)){ // Check file type is allow or not
            http_response_code(400);
            die(json_encode(array('code'=>4,'message'=>'This type of file is not allowed')));
        }else if($file_size>104857600){ // Check file size is less than 100M
            http_response_code(400);
            die(json_encode(array('code'=>4,'message'=>'This file is larger than 100M')));
        }else{ // Upload task
            $file_path = "upload/".$file_name;
            move_uploaded_file($file_tmp, $file_path);
            $file_path_name = $file_name;
            $message = "Submit successful";
            http_response_code(200);
            submit_absence_form($user,$dayoff,$description,$file_path_name);
            die(json_encode(array('code'=>0,'message'=>'Submit successful')));
        }
    }

?>
<?php
    ob_end_flush();
?>