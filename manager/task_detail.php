<?php
    session_start();
    ob_start();
    require_once('../admin/db.php');
    if (!isset($_SESSION['user'])) {
        header('Location: login.php');
        exit();
    }else if(get_info_employee_byuser($_SESSION['user'])['role'] != 'manager' ){
        move_page_manager(get_info_employee_byuser($_SESSION['user'])['role']);
        exit();
    }
	function move_page_manager($role){
        if($role == 'employee'){
			header('Location: ../index.php');
		}
		else if($role == 'manager'){
			header('Location: .index.php');
		}
		else{
			header('Location: ../admin/index.php');
		}
    }

    $department = get_department_byuser($_SESSION['user'])['department'];

    $error = '';
    $message = "";
    $status = "";

    $reject_submit = "";
    $complete_submit = "";
    
    $rating = '';
    $is_late = '';

    $deadline = '';

    $submit_date = '';
    $description = '';
    $file = '';
    $deadlineAdd = '';
    $is_extend = '';
    $id = "";
    if(isset($_POST['is_task_cancel']) && isset($_POST['cancel_id_task'])){
        if($_POST['is_task_cancel'] == 1)
        {
            $id = $_POST['cancel_id_task'];
            update_task_status($id,'Canceled');
            update_modified_time($id);
        }
    }
    if(isset($_POST['complete-submit'])){
        if(isset($_POST['rating'])||isset($_POST['is_late'])||isset($_POST['id_task_complete'])){
            $rating = isset($_POST['rating'])?$_POST['rating']:'';
            $is_late = $_POST['is_late'];
            $id = $_POST['id_task_complete'];

            if(empty($id)){
                $error = "Missing id value";
            }else if(empty($rating)){
                $error = "Please rating for this submit task";
            }else{
                if($is_late){
                    $submit_time_status = 'On time';
                }else{
                    $submit_time_status = 'Late';
                }
                $message = "This task is done !!!";
                submit_complete_feedback($id,$rating,$submit_time_status);
                update_task_status($id,'Completed');
                update_modified_time($id);
                header("Refresh:0");
            }
        }
    }

    if(isset($_POST['reject-submit'])){
        if(isset($_POST['description'])||isset($_FILE['file'])||isset($_POST['is_extend'])||isset($_POST['id_task_reject'])){
            $description = trim($_POST['description']);
            $is_extend = $_POST['is_extend'];
            $id = $_POST['id_task_reject'];

            $file = $_FILES['file'];
            $errors= array();
            $file_name = $file['name'];
            $file_size =$file['size'];
            $file_tmp =$file['tmp_name'];
            $file_extension = explode('.',$file_name);
            $file_ext=strtolower(end($file_extension));

            $extensions= array("txt","doc","docx","xls","xlsx","jpg","png","mp3","mp4","pdf","rar","zip","pptx","html","sql","ppt","jpeg");
            
            
            if(empty($id)){ // Check description is empty or not
                $error = "Missing id value";
            }else if(empty($description)){ // Check description is empty or not
                $error = "Please enter your description";
            }else if($is_extend){
                if(!empty($_POST['deadlineAdd'])){
                    $deadlineAdd = $_POST['deadlineAdd'];

                    if(!$file_name){
                        $message = "Send reject feedback successful";
                        submit_reject_feedback($id,$description,'',$is_extend);
                        update_deadline($id,$deadlineAdd);
                        update_task_status($id,'Rejected');
                        update_modified_time($id);
                        header("Refresh:0");
                    }else if(!in_array($file_ext,$extensions)){ // Check file type is allow or not
                        $error = "This type of file is not allowed";
                    }else if($file_size>104857600){ // Check file size is less than 100M
                        $error = "This file is larger than 100M";
                    }else{ // Upload task
                        $file_path = "../upload/".$file_name;
                        move_uploaded_file($file_tmp, $file_path);
                        $file_path_name = "upload/".$file_name;
                        $message = "Send reject feedback successful";
                        submit_reject_feedback($id,$description,$file_path_name,$is_extend);
                        update_deadline($id,$deadlineAdd);
                        update_task_status($id,'Rejected');
                        update_modified_time($id);
                        header("Refresh:0");
                    }
                }else{
                    $error = "Please choose extend deadline date";
                }
            }else {
                if(!$file_name){
                    $message = "Send reject feedback";
                    submit_reject_feedback($id,$description,'',$is_extend);
                    update_task_status($id,'Rejected');
                    update_modified_time($id);
                    header("Refresh:0");
                }else if(!in_array($file_ext,$extensions)){ // Check file type is allow or not
                    $error = "This type of file is not allowed";
                }else if($file_size>104857600){ // Check file size is less than 100M
                    $error = "This file is larger than 100M";
                }else{ // Upload task
                    $file_path = "../upload/".$file_name;
                    move_uploaded_file($file_tmp, $file_path);
                    $file_path_name = "upload/".$file_name;
                    $message = "Send reject feedback successful";
                    submit_reject_feedback($id,$description,$file_path_name,$is_extend);
                    update_task_status($id,'Rejected');
                    update_modified_time($id);
                    header("Refresh:0");
                }
            }
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
	<link rel="stylesheet" href="/style.css">
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
    <input type="hidden" name="page" id="page" value="manager-task-detail">

    <div class="container-fluid admin-section-header">	
        <div class="row">
            <div class="col-sm-10 col-md-10 col-lg-10 col-xl-10 admin-logo">
            Company System | <?=$department?>
			</div>
			<div class="col-sm-1 col-md-1 col-lg-1 col-xl-1 admin-login-info">
				<a href="account.php">Welcome, <?= $_SESSION['name'] ?></a>
			</div>
			<div class="col-sm-1 col-md-1 col-lg-1 col-xl-1 admin-login-info">
				<a href="../logout.php">Log out</a>
			</div>
		</div>
		<div class="row h-100">
			<div class="  col-md-2 col-lg-2 col-xl-2  admin-section1">
				<nav class="  navbar-expand-lg navbar-light  ">
					<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
						<span class="navbar-toggler-icon"></span>
					</button>
		
					<div class="collapse navbar-collapse" id="navbarSupportedContent">
						<ul class="navbar-nav" style="flex-direction: column;">
                            <li class="nav-item active-menu">
								<a class="nav-link p20" href="index.php"><i class="fas fa-tasks"></i>List Task</a>
							</li>
							
							<li class="nav-item">
								<a class="nav-link p20" href="dayoff.php"><i class="fas fa-address-book"></i>  Absence Request</a>
							</li>
                            <li class="nav-item">
								<a class="nav-link p20" href="absence.php"><i class="fas fa-address-book"></i>  Absence </a>
							</li>
						</ul>
					</div>
				</nav>
			</div>
			<div class="col-md-10 col-lg-10 col-xl-10 ">
				<div class="bg-light mt-4 text-dark p-2">
                    <div class="admin-panel-section-header ">
                        <h2>Task Detail</h2>
                    </div>
                    <div class="account-container" style="display: block;">
                        <table class="table-hover">
                            <?php 
                                $id = $_GET['id'];
                                $result = get_taskdetail_byid($id); 
                                if($result['code'] == 0){
                                    $data = $result['data'];
                                    $deadline = $data['deadline'];
                                    ?>    
                                        <tr>
                                            <th>ID Task</th>
                                            <td><?=$data['id']?></td>
                                        </tr>
                                        <tr>
                                            <th>Title</th>
                                            <td><?=$data['title']?></td>
                                        </tr>
                                        <tr>
                                            <th>Description</th>
                                            <td><?=$data['description']?></td>
                                        </tr>
                                        <tr>
                                            <th>Person</th>
                                            <td><?=$data['person']?></td>
                                        </tr>
                                        <tr>
                                            <th>Deadline</th>
                                            <td><?=$data['deadline']?></td>
                                        </tr>
                                        <tr class="mt-1 mb-3 pb-3 border-bottom border-info">
                                            <th>Task file</th>
                                            <td><a href="../<?=$data['file']?>"><?=convert_to_filename($data['file'])?></a></td>
                                        </tr>
                                        <?php
                                            $submit_list = get_submit_list_by_id($id);
                                            if(!$submit_list['code']){
                                                $submit_data = $submit_list['data'];
                                                foreach($submit_data as $key=>$row){
                                                    $submit_date = $row['submit_day'];
                                                    ?>
                                                        <tr>
                                                            <th>Submit_<?=$key+1?></th>
                                                            <td>-----------</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Description</th>
                                                            <td><?= $row['sm_description']?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>File</th>
                                                            <td><a href="../<?=$row['sm_file']?>"><?=convert_to_filename($row['sm_file'])?></a></td>
                                                        </tr>
                                                        <tr class="mt-1 mb-3 pb-3 border-bottom border-info">
                                                            <th >Date</th>
                                                            <td><?= $row['submit_day']?></td>
                                                        </tr>
                                                    <?php
                                                }
                                            }
                                        ?>
                                        <tr>
                                            <td>Status</td>
                                            <?php
                                                $status = $data['status'];
                                            ?>
                                            <?=status_ui($data['status'])?>
                                        </tr>
                                    
                                    <?php 
                                        if($data['status'] == 'New'){
                                            ?>
                                            <tr id="action-task">
                                                    <td>Action</td>
                                                    <td>
                                                        <form class="submit-form"   method="POST" enctype="multipart/form-data">

                                                            <button type="submit" class="btn btn-dark " id="reject-btn">Cancel</button>
                                                            <input type="hidden" name="is_task_cancel" value="1">
                                                            <input type="hidden" name="cancel_id_task" value="<?= $id ?>">
                                                        </form>

                                                    </td>
                                                    
                                                </tr>
                                            </table>
                                            <?php
                                        }
                                        if($data['status'] == 'Waiting'){
                                            ?>
                                                <tr id="action-task">
                                                    <td>Action</td>
                                                    <td>
                                                        <button class="btn btn-primary " id="complete-btn">Complete</button> | 
                                                        <button class="btn btn-danger " id="reject-btn">Reject</button>
                                                    </td>
                                                </tr>
                                            </table>
                                            <form class="submit-form" id="complete-form" style="display: none;" method="POST" enctype="multipart/form-data">
                                                <div class="form-group border-top border-bottom border-info text-center">
                                                    <h2>Complete feedback</h2>
                                                </div>
                                                    <div class="form-group col-sm-12 col-md-3 ml-auto mr-auto">
                                                        <select name="rating" class="form-control">
                                                            <option value="" disabled selected>Rating</option>
                                                            <?php
                                                                if(is_submit_late($deadline,$submit_date)){
                                                                    echo "<option value='Good'>Good</option>";
                                                                }
                                                            ?>
                                                            <option value='OK'>OK</option>
                                                            <option value='Bad'>Bad</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group" id="complete-error-message">
                                                    <?php
                                                        if(!empty($error)){
                                                            echo "<div class='alert alert-danger text-center'>$error</div>";
                                                        }
                                                    ?>
                                                    </div>
                                                    <div class="form-row">
                                                        <div class="form-group col-sm-12 col-md-6">
                                                            <button type="submit" id="upload-btn" class="btn btn-primary col-12 col-sm-12">Submit</button>
                                                        </div>
                                                        <div class="form-group col-sm-12 col-md-6">
                                                            <button class="btn btn-dark col-12 col-sm-12 back-btn">Back</button>
                                                        </div>
                                                    </div>
                                                    <input id="is_complete" name="complete-submit" type="hidden" value="0">
                                                    <input name="is_late" type="hidden" value="<?=is_submit_late($deadline,$submit_date)?>">
                                                    <input name="id_task_complete" type="hidden" value="<?=$id?>">
                                            </form>

                                            <form class="submit-form" id="reject-form" style="display: none;" method="POST" enctype="multipart/form-data">
                                                <div class="form-group border-top border-bottom border-info text-center">
                                                    <h2>Reject feedback</h2>
                                                </div>
                                                <div class="form-group">
                                                    <label for="description">Feedback detail</label>
                                                    <textarea name="description" class="form-control" id="description" rows="3"><?=$description?></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" name="file" id="file">
                                                        <label class="custom-file-label" for="file">Choose file</label>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-sm-12 col-md-3">
                                                        <label for="is_extend">Extend deadline for this task?</label>
                                                        <select id="is_extend" name="is_extend" class="form-control">
                                                            <option value='0' selected>No</option>
                                                            <option value='1'>Yes</option>
                                                        </select>
                                                    </div>
                                                    <div id="deadline-box" class="form-group col-sm-12 col-md-3" style="display: none;">
                                                        <label for="deadlineAdd">Deadline</label>
                                                        <input id="deadlineAdd" name="deadlineAdd"  value ="<?= $deadlineAdd ?>" class="form-control" type="date" id="deadlineAdd" min='1899-01-01' max='2025-10-10'>
                                                    </div>
                                                </div>
                                                <div class="form-group" id="reject-error-message">
                                                    <?php
                                                        if(!empty($error)){
                                                            echo "<div class='alert alert-danger text-center'>$error</div>";
                                                        }
                                                    ?>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-sm-12 col-md-6">
                                                        <button type="submit" id="upload-btn" class="btn btn-primary col-12 col-sm-12">Submit</button>
                                                    </div>
                                                    <div class="form-group col-sm-12 col-md-6">
                                                        <button class="btn btn-dark col-12 col-sm-12 back-btn">Back</button>
                                                    </div>
                                                </div>
                                                <input id="is_reject" name="reject-submit" type="hidden" value="0">
                                                <input name="id_task_reject" type="hidden" value="<?=$id?>">
                                                <input name="deadline" type="hidden" value="<?=$deadline?>">
                                            </form>
                                                        
                                            <?php
                                        }
                                }
                            ?>
                    </div>
				</div>
			</div>		
		</div>
	</div>


	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	<script src="/main.js"></script>
</body>

</html>

<?php
    ob_end_flush();
?>