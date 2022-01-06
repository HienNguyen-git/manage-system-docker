<?php
    session_start();
    require_once('db.php');
    if(!isset($_SESSION['user'])){
        header('Location: login.php');
    }
    $user = $_SESSION['user'];
    if( !is_password_changed($user) ){
        header('Location: change_password.php');
        exit();
    }
    $id = $_GET['id'];
    $error = '';
    $message = "";
    $description = '';

    if (isset($_POST['description']) && isset($_FILES['file'])) {
        $description = $_POST['description'];
        
        $file = $_FILES['file'];
        $errors= array();
        $file_name = $file['name'];
        $file_size =$file['size'];
        $file_tmp =$file['tmp_name'];
        $file_extend=explode('.',$file_name);
        $file_ext = strtolower(end($file_extend));
        $extensions= array("txt","doc","docx","xls","xlsx","jpg","png","mp3","mp4","pdf","rar","zip","pptx","html","sql","ppt","jpeg");
        if(empty($description)){ // Check description is empty or not
            $error = "Please enter your description";
        }else if(!$file_name){
            $error = "Please upload your file";
        }else if(!in_array($file_ext,$extensions)){ // Check file type is allow or not
            $error = "This type of file is not allowed";
        }else if($file_size>104857600){ // Check file size is less than 100M
            $error = "This file is larger than 100M";
        }else{ // Upload task
            $file_path = "upload/".$file_name;
            $date = date("Y-m-d");
            move_uploaded_file($file_tmp, $file_path);
            $message = "Submit successful";
            submit_task($id,$description,$file_path);
            update_modified_time($id);
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
	<title>Home Page</title>
	
</head>

<body>
<?php
    if(!empty($error)){
        echo "<div class='alert alert-danger text-center' style='margin-bottom: 0 !important'>Something went wrong! Check your submit again</div>";
    }
    if(!empty($message)){
        echo "<div class='alert alert-primary text-center' style='margin-bottom: 0 !important'>$message</div>";
    }
?>
</script>
<input type="hidden" id="page" name="page" value="task">
<nav class="navbar navbar-expand-lg bg-info navbar-dark">
	<div class="container">
		<a href="./" class="navbar-brand navbar-header" style="font-size: 3rem;">FINAL PROJECT</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarSupportedContent">
		<ul class="navbar-nav ml-auto h4">
			<li class="nav-item ">	
			<a class="nav-link active" href="./">TASK</a>
			</li>
			<li class="nav-item">
			<a class="nav-link" href="absence.php">ABSENCE</a>
			</li>
			<li class="nav-item">
			<a class="nav-link" href="account.php">ACCOUNT</a>
			</li>
			<li class="nav-item">
			<a class="btn btn-danger nav-link text-light" href="logout.php">LOGOUT</a>
			</li>
		</ul>
		</div>
	</div>
</nav>

<div class="container pb-2">
        <?php
            $data = get_task_by_id($id)['data'];
        ?>
		<h1 class="mt-3 text-secondary">TASK INFORMATION</h1>
        <h3 class="mt-1 mb-3 pb-3 border-bottom border-info text-light" ><?=$data['title']?></h3>
		<div class="ml-auto mr-auto task-container">
            <table>
                <tr>
                    <th>Status:</th>
                    <?php
                        status_ui($data['status']);
                        if($data['status']=='New'){
                            echo "<td><a href='start_task.php?id=$id' class='btn btn-primary'>Start now</a></td>";
                        }
                    ?>
                </tr>
                <tr>
                    <th>Title:</th>
                    <td><?=$data['title']?></td>
                </tr>
                <tr>
                    <th>Detail:</th>
                    <!-- <td>Ahihi</td> -->
                    <td><?=$data['description']?></td>
                </tr>
                <tr>
                    <th>Deadline</th>
                    <td><?=$data['deadline']?></td>
                </tr>
                <tr>
                    <th>File</th>
                    <?php 
                        $filename = explode('/',$data['file'])[1];
                    ?>
                    <td><a href="<?=$data['file']?>"><?=$filename?></a></td>
                </tr>
        	</table>

            <?php
                if($data['status']=="Rejected" || is_rejected($id)){
                    $result = get_feedback_reject_task($id);
                    if(!$result['code']){
                        $feedback_data = $result['data'];
                        ?>
                            <h3 class="mt-1 mb-3 pb-3 text-center border-bottom border-info text-light" >Reject feedback</h3>
                        <?php

                        foreach($feedback_data as $key=>$row){
                            ?>
                                <table style="width: 0%;">
                                    <tr>
                                        <th>Feedback: <?=$key+1?></th>
                                        <td><?=$row['description']?></td>
                                    </tr>
                                    <tr>
                                        <th>File:</th>
                                        <?php 
                                            $filename = '-';
                                            if($row['file']!==''){
                                                $filename = explode('/',$row['file'])[1];
                                            }
                                        ?>
                                        <td><a href="<?=$row['file']?>"><?=$filename?></a></td>
                                    </tr>
                                    <tr>
                                        <th>Extend deadline:</th>
                                        <?php
                                            if($row['extend_deadline']){
                                                echo "<td class='text-primary'><i class='fas fa-check'> Yes</i></td>";
                                            }else{
                                                echo "<td class='text-danger'><i class='fas fa-times-circle'> No</i></td>";
                                            }
                                        ?>
                                    </tr>
                                </table>
                                <p class="mt-1 mb-5 border-bottom border-info text-light" style="width:50%;"></p>
                            <?php
                        }
                    }
                }
                if($data['status']=="Completed"){
                    $feedback_data = get_feedback_complete_task($id);
                    if(!$feedback_data['code']){
                        $row = $feedback_data['data'];
                    }
                    ?>
                        <h3 class="mt-1 mb-3 pb-3 text-center border-bottom border-info text-light" >Complete feedback</h3>
                        <table>
                            <tr>
                                <th>Rating:</th>
                                <?=status_ui($row['rating'])?>
                            </tr>
                            <tr>
                                <th>Time submit:</th>
                                <?=status_ui($row['time_submit'])?>
                            </tr>
                        </table>
                    <?php
                }
                if($data['status']=='In progress' || $data['status']=='Rejected'){
                    ?>
                        <button class="btn btn-success submit-btn col-12 col-sm-4 mb-5" style="display: block;">Create submit form</button>
                        <form class="submit-form" id="task-form" style="display: none;" method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea name="description" class="form-control" id="description" style="min-width:300px; max-width:500px" rows="3"><?=$description?></textarea>
                            </div>
                            <div class="form-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="file" id="file">
                                    <label class="custom-file-label" for="file">Choose file</label>
                                </div>
                            </div>
                            <div class="form-group" id="error-message">
                                <?php
                                    if(!empty($error)){
                                        echo "<div class='alert alert-danger'>$error</div>";
                                    }
                                ?>
                            </div>
                                <div class="form-group">
                                <button type="submit" id="upload-btn" class="btn btn-primary col-12 col-sm-12`">Submit</button>
                            </div>
                        </form>
                    <?php
                }
            ?>
		</div>
</div>
	<?php
    if($data['status']=="In progress" || $data['status']=='Rejected'){
        ?>
        <script src="/main.js"></script> <!-- Sử dụng link tuyệt đối tính từ root, vì vậy có dấu / đầu tiên -->
        <?php
    }
    ?>	
</body>
</html>