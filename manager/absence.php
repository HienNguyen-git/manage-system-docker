<?php
	session_start();
	ob_start();
	require_once('../db.php');
	if(!isset($_SESSION['user'])){
		header('Location: /login.php');
	}
	$user = $_SESSION['user'];
	if( !is_password_changed($user) ){
		header('Location: /change_password.php');
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

   	$error = '';
    $message = "";
    $description = '';
	$dayoff = '';
	$status = '';
	$is_lock = '';
	$dayoff_left = 1;

	$absenceInfo = get_absence_info($user);
	if(!$absenceInfo['code']){
		$data = $absenceInfo['data'];
		$day_off_permit = $data['total_dayoff'];
		$dayoff_used = $data['dayoff_used'];
		$dayoff_left = $data['dayoff_left'];
	}

    if (isset($_POST['description']) && isset($_FILES['file']) && isset($_POST['dayoff'])) {
        $description = $_POST['description'];
        $file = $_FILES['file'];
		$dayoff = $_POST['dayoff'];

        $errors= array();
        $file_name = $file['name'];
        $file_size =$file['size'];
        $file_tmp =$file['tmp_name'];
		$file_extension = explode('.',$file_name);
        $file_ext=strtolower(end($file_extension));


        $extensions= array("txt","doc","docx","xls","xlsx","jpg","png","mp3","mp4","pdf","rar","zip","pptx","html","sql","ppt","jpeg");
        if(empty($description)){ // Check description is empty or not
            $error = "Please enter your description";
        }else if(empty($dayoff)){
			$error = "Please enter your day off";
		}else if($dayoff>$dayoff_left){
			$error = "Your day off is too much";
		}else if(!$file_name){
            $message = "Submit successful";
            submit_absence_form($user,$dayoff,$description,'');
			$description = '';
			$dayoff = '';
        }else if(!in_array($file_ext,$extensions)){ // Check file type is allow or not
            $error = "This type of file is not allowed";
        }else if($file_size>104857600){ // Check file size is less than 100M
            $error = "This file is larger than 100M";
        }else{ // Upload task
		
            $file_path = "../upload/".$file_name;
			
            move_uploaded_file($file_tmp, $file_path);
			$file_path_name = "upload/".$file_name;

            $message = "Submit successful";
            submit_absence_form($user,$dayoff,$description,$file_path_name);
			$description = '';
			$dayoff = '';
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

<body style="background-color: lightblue;">

<input type="hidden" id="page" name="page" value="manager-absence">
<?php
    if(!empty($error)){
        echo "<div class='alert alert-danger text-center' style='margin-bottom: 0 !important'>Something went wrong! Check your submit again</div>";
    }
    if(!empty($message)){
        echo "<div class='alert alert-primary text-center' style='margin-bottom: 0 !important'>$message</div>";
    }
?>
<div class="container pb-2" style="height: 70vh;">
	<h1 class="mt-3 text-secondary">ABSENCE MANAGE</h1>
	<h3 class="mt-1 mb-3 pb-3 border-bottom border-info text-light">Your absence info</h3>
	<a class="btn btn-dark  col-sm-12 col-md-6 mb-3" href="index.php">Back</a>
		<div class="ml-auto mr-auto absence-container">
			<?php
				$absenceInfo = get_absence_info($user);
				$day_off_permit = 0;
				if(!$absenceInfo['code']){
					$data = $absenceInfo['data'];
					$day_off_permit = $data['total_dayoff'];
					$dayoff_used = $data['dayoff_used'];
					$dayoff_left = $data['dayoff_left'];
				}
				?>
					<p class="col-sm-12 col-md-6 text-center text-md-left"><strong>Day off Permit: </strong><?=$day_off_permit?> | <strong>Using: </strong><?=$data['dayoff_used']?> | <strong>The Rest: </strong><?=$data['dayoff_left']?></p>
					
					<?php
			?>
		<?php
			$absenceHistory = get_absence_history($user);
			if($absenceHistory['code']==2){
				?>
					
					<button class="btn btn-success submit-btn col-sm-12 col-md-6 mb-3 ">Create request absence form</button>
				<?php
			}
			if(!$absenceHistory['code']){
				$data = $absenceHistory['data'];
				if(!$dayoff_left){}
				else if($data[0]['status']=='Waiting'){
					$status = 'Waiting';
				}else if(!is_absence_form_unlock($user)){
					$is_lock = true;
				}else{
					?>
						<button class="btn btn-success submit-btn col-sm-12 col-md-6 mb-3">Create request absence form</button>
					<?php
				}
				?>
					<table class="table table-bordered table-light table-hover text-center ali" id="absence-history" style="border-color:black;">
						<thead>
							<tr>
								<th scope="col">#</th>
								<th scope="col">Create date</th>
								<th scope="col">Number of day off</th>
								<th scope="col">Reason</th>
								<th scope="col">File</th>
								<th scope="col">Status</th>
								<th scope="col">Approval date</th>
							</tr>
						</thead>
						<tbody>
							<?php
								foreach($data as $row){
								?>
									<tr>
										<th scope="row"><?=$row['id']?></th>
										<td><?=$row['create_date']?></td>
										<td><?=$row['number_dayoff']?></td>
										<td><?=$row['reason']?></td>
										<?php
											if(!$row['file']){
												echo "<td>-</td>";
											}else{
                                            	$filename = explode('/',$row['file'])[1];
												echo "<td><a href='../".$row['file']."'>".$filename."</a></td>";
											}
										?>
										<?php
										status_ui($row['status']);

										if($row['approval_date']){
											echo "<td>".$row['approval_date']."</td>";
										}else{
											echo "<td>-</td>";
										}
										?>
									</tr>
								<?php
								}
							?>
						</tbody>
					</table>
				<?php
			}else{
				echo "<div class='alert alert-primary text-center' id='absence-history' style='flex-basis: 100%'>You haven't requested any absences yet!</div>";
			}
		?>
				<form class="submit-form" id="task-form" style="display:none; flex-basis: 100%;" method="POST" enctype="multipart/form-data">
					<div class="form-row">
						<div class="form-group col-sm-12 col-md-9">
							<input value="<?=$description?>" name="description" class="form-control" id="description" placeholder="Reason"></input>
						</div>
						<div class="form-group col-sm-12 col-md-3">
							<select name="dayoff" class="form-control" id="dayoff">
							<option value="" disabled selected>Number of day off</option>
							<?php
								for ($i = 1; $i <= $dayoff_left; $i++){
									if($dayoff==$i){
										echo "<option value='$i' selected>$i</option>";
									}else{
										echo "<option value='$i'>$i</option>";
									}
								}
							?>
							</select>
						</div>
						<div class="form-group col-sm-12 col-md-9">
							<div class="custom-file">
								<input type="file" class="custom-file-input" name="file" id="file">
								<label class="custom-file-label" for="file">Choose file</label>
							</div>
						</div>
					</div>
					<div class="form-group" id="error-message">
						<?php
							if(!empty($error)){
								echo "<div class='alert alert-danger text-center'>$error</div>";
							}
						?>
					</div>
					<div class="form-group">
						<div class="form-group">
							<button type="submit" id="upload-btn" class="btn btn-primary mb-3 col-sm-12 col-md-4">Submit</button>
							<button type="submit" id="back-btn" class="btn btn-secondary mb-3 col-sm-12 col-md-4">Back</button>
						</div>
					</div>
				</form>
		</div>

	</div>
	<?php
		if(!$status||!$is_lock || !$dayoff_left){
			?>
				<script src="/main.js"></script>
			<?php
		}
	?>
	
</body>

</html>
<?php
    ob_end_flush();
?>