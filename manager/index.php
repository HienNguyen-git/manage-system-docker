<?php
    session_start();
	require_once('../admin/db.php');
	//is_login
    if (!isset($_SESSION['user'])) {
		header('Location: ../login.php');
        exit();
    }
	$user =get_info_employee_byuser($_SESSION['user']);
	
	//is_change_pass and check role
	if( !is_password_changed($_SESSION['user']) ){
		header('Location: ../change_password.php');
		exit();
	}
	else if($user['role'] != 'manager' ){
        move_page_manager($user['role']);
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
	//save department
	$department = get_department_byuser($_SESSION['user'])['department'];
	//file
	$error = '';
	$success = '';
	$title = '';
	$detail = '';
	$deadline = '';
	$taskEmployeeAdd = '';

	if(isset($_POST['taskTitleAdd']) && isset($_POST['taskDetailAdd']) && isset($_POST['deadlineAdd'])
	&& isset($_FILES['file']) && isset($_POST['taskEmployeeAdd'])){
		$title = $_POST['taskTitleAdd'];
		$detail = $_POST['taskDetailAdd'];
		$deadline = $_POST['deadlineAdd'];
		$taskEmployeeAdd = $_POST['taskEmployeeAdd'];
		
		$file = $_FILES['file'];
		$file_name = $file['name'];
		echo $file_name;
		$file_size = $file['size'];
		$file_tmp = $file['tmp_name'];
		$tmp = explode('.', $file_name);
		$file_ext = end($tmp);
		// $file_ext = strtolower(end(explode('.',$file_name)));
        
		$extensions= array("txt","doc","docx","xls","xlsx","jpg","png","mp3","mp4","pdf","rar","zip","pptx","html","sql","ppt","jpeg");
		if(empty($title)){
			$error = "Please enter title task";
		}else if(empty($detail)){
			$error = "Please enter detail task";
		}else if(empty($deadline)){
			$error = "Please enter deadline task";
		}else if(empty($taskEmployeeAdd)){
			$error = "Please enter person do task";
		}else if(!$file_name){
			$error = "Please upload file task";
		}else if(!in_array($file_ext,$extensions)){
			$error = "This type of file is not allowed";
		}else if($file_size > 104857600){
			$error = "This file is larger than 100M";
		}else{
			$file_path = "../upload/".$file_name;
			move_uploaded_file($file_tmp,$file_path);
			$file_path_name = $file_name;
			$success ="submit successful";
			add_task($title,$detail,$taskEmployeeAdd,$deadline,$file_path_name);
		}
	}else{
		$error = "Please fill all";
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
	<!-- <link rel="stylesheet" href="/style.css"> Sử dụng link tuyệt đối tính từ root, vì vậy có dấu / đầu tiên -->
	<link rel="stylesheet" href="/style.css">
	<title>Home Page</title>
</head>

<body>
	<input type="hidden" name="page" id="page" value="manager-index">
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
						</form>
					</div>
				</nav>
			</div>
			<div class="col-md-10 col-lg-10 col-xl-10  ">
				<div class="bg-light mt-4 text-dark p-2">
					<div class="admin-panel-section-header">
						<h2>List Tasks</h2>
						<!-- <a class="addbtn"  data-toggle="modal" data-target="#add-task">Add Task</a> -->
						<a class="addbtn"  href="add_task.php">Add Task</a>
					</div>
					<table class="table-hover" cellpadding="10" cellspacing="10" border="1" style="width: 100%; margin-top:20px">
						<tr class="header">
							<td>ID</td>
							<td>Title</td>
							<td>Person</td>
							<td>Deadline</td>
							<td>Status</td>
							<td>Action</td>
						</tr>
						<tbody id="tbody">
							<?php 
								$result = get_tasks($department); 
								if($result['code'] == 0){
									$data = $result['data'];
									foreach($data as $row){
										?>
										<tr class="item">
											<td><?= $row['id'] ?></td>
											<td><?= $row['title'] ?></td>
											<td><?= $row['person'] ?></td>
											<td><?= $row['deadline'] ?></td>
											<?=status_ui($row['status'])?>
											<td >
												<a href="task_detail.php?id=<?= $row['id']?>" class="btn btn-success">View detail</a>
											</td>
										</tr>
										<?php
									}
								}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
    </div>
	
	<!-- Add Modal -->
	<div id="add-task" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <hp class="modal-title">Add Task</hp>
                    <button type="button" class="close" data-dismiss="modal" >&times;</button>
                </div>
                <form id="add-form" method="POST" novalidate enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="taskTitleAdd">Title Task</label>
                            <input name="taskTitleAdd" required class="form-control" type="text" placeholder="Department Name" id="taskTitleAdd">
                        </div>
						<div class="form-group">
							<label for="taskDetailAdd">Detail Task</label>
							<textarea id="taskDetailAdd" name="taskDetailAdd" rows="4" class="form-control" placeholder="Department Detail"></textarea>
						</div>
                        <div class="form-group">
                            <label for="deadlineAdd">Deadline</label>
                            <input name="deadlineAdd" required class="form-control" type="date" placeholder="Department Number" id="deadlineAdd">
                        </div>
						<div class="form-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="file" id="file">
                                <label class="custom-file-label" for="file">Choose file</label>
                            </div>
                        </div>
						
						<div class="form-group">
                            <label for="taskEmployeeAdd">Employee Name</label>
							<select id="taskEmployeeAdd" class="form-control" name="taskEmployeeAdd" required>
								<option value="" disabled selected>Employee Name</option>
								<?php 
									$result = get_employee_bydepartment($department);
									// print_r($result);
									if($result['code'] == 0){
										$data = $result['data'];
										foreach($data as $row){
											// print_r($row['username']);
											?>
												<option value="<?= $row['username'] ?>" ><?= $row['username'] ?></option>
											<?php
										}
									}
								?>
							</select>
                        </div>
						<div class="form-group" id="error-message">
                                <?php
                                    if(!empty($error)){
                                        echo "<div class='alert alert-danger'>$error</div>";
                                    }
									if(!empty($success)){
                                        echo "<div class='alert alert-danger'>$success</div>";
                                    }
                                ?>
                            </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary px-5 mr-2 " id="add-btn">Add</button>
                        </div>
                    </div>
                </form>
            </div>  
        </div>
    </div>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	<!-- <script src="/main.js"></script> Sử dụng link tuyệt đối tính từ root, vì vậy có dấu / đầu tiên -->
	<script src="/main.js"></script>
	<!-- <script>
		$(".custom-file-input").on("change", function () {
			var fileName = $(this).val().split("\\").pop();
			$(this).siblings(".custom-file-label").addClass("selected").html(fileName);
		});
	</script>
	<script>
		//btn upload
		const addBtn = document.getElementById('add-btn');
		addBtn.disabled = true;
		const taskTitleAdd = document.getElementById('taskTitleAdd');
		console.log(taskTitleAdd.value);
		// if()
		//file
		const uploadFile = document.querySelector('#file')
		uploadFile.addEventListener('change', e=>{      
			const file = e.target.files[0];
			const fileSize = file.size;
			const fileName = file.name;
			const fileExt = fileName.split('.').pop().toLowerCase();
			const type_list = ["txt","doc","docx","xls","xlsx","jpg","png","mp3","mp4","pdf","rar","zip","pptx","sql","ppt","jpeg"];
			if(fileSize === 0){
				handleErrorMessage('Please upload file');
			}else if(!type_list.includes(fileExt)){
				handleErrorMessage('This type of file is not allowed');
			}else if(fileSize > 100*Math.pow(1024,2)){
				handleErrorMessage('This file is larger than 100M');
			}else{
				handleSuccessMessage('This file is ok')
			}
		});
		const messageBox = document.querySelector('#error-message')
		//thành công
		function handleSuccessMessage(message){
			messageBox.innerHTML = '';
			messageBox.insertAdjacentHTML('beforeend',`<div class='alert alert-success'>${message}</div>`)
		}
		//lỗi
		function handleErrorMessage(message){
			messageBox.innerHTML = '';
			messageBox.insertAdjacentHTML('beforeend',`<div class='alert alert-danger'>${message}</div>`)
			uploadBtn.disabled = true;
		}

		//thêm
		// const addForm = document.querySelector('#add-form');
        // addForm.addEventListener('submit', async (e)=>{
        //     e.preventDefault();
        //     const taskTitleAdd = document.querySelector('#taskTitleAdd').value;
        //     const taskDetailAdd = document.querySelector('#taskDetailAdd').value;
		// 	const deadlineAdd = document.querySelector('#deadlineAdd').value;
		// 	const uploadFile = document.querySelector('#file');
		// 	const taskEmployeeAdd = document.querySelector('#taskEmployeeAdd').value;
            
		// 	console.log(taskTitleAdd,taskDetailAdd,deadlineAdd,fileAdd,taskEmployeeAdd);
		// 	const sendRequest = await fetch('add_department.php',{
        //         method: 'POST',
        //         body: JSON.stringify({taskTitleAdd,taskDetailAdd,deadlineAdd})
        //     })
        //     const res = await sendRequest.json();
        //     reloadPage(res)
        // })
	</script>
	<script>
		function reloadPage(res){
            if(res.code===0){
                location.reload();
            }
        }
	</script> -->
</body>

</html>
