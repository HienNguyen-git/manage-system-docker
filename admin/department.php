<?php
    session_start();
	require_once('db.php');
    if (!isset($_SESSION['user'])) {
        header('Location: login.php');
        exit();
    }
?>
<?php
    $success = '';
    $error = '';

    $departmentNameUpdate = '';
    $departmentNumUpdate = '';
    $departmentManagerUpdate = '';
    $departmentDetailUpdate = '';

	$is_edit = isset($_POST['is_edit']) ? $_POST['is_edit'] : '';
	if($is_edit){

		if (isset($_POST['departmentNameUpdate']) && isset($_POST['departmentNumUpdate']) && isset($_POST['departmentManagerUpdate'])
		&& isset($_POST['departmentDetailUpdate']))
		{
			// echo 'ahoho   ';
			$departmentNameUpdate = $_POST['departmentNameUpdate'];
			$departmentNumUpdate = $_POST['departmentNumUpdate'];
			$departmentManagerUpdate = $_POST['departmentManagerUpdate'];
			$departmentDetailUpdate = $_POST['departmentDetailUpdate'];
			// echo $departmentNameUpdate . ' ' . $departmentNumUpdate . ' ' . $departmentManagerUpdate . ' ' . $departmentDetailUpdate;
			if (empty($departmentNameUpdate)) {
				$error = 'Please enter department name';
			}
			else if (empty($departmentNumUpdate)) {
				$error = 'Please enter department number';
			}
			else if (empty($departmentManagerUpdate)) {
				$error = 'Please enter department Manager Name';
			}
			else if (empty($departmentDetailUpdate)) {
				$error = 'Please enter department detail';
			}
			else {
				$currentManager = current_user_of_department($departmentNameUpdate);
				update_to_manager($departmentManagerUpdate);
				update_to_employee($currentManager);
				update_managerName_department($departmentManagerUpdate,$departmentNameUpdate);
				update_total_dayoff($currentManager,12);
				update_total_dayoff($departmentManagerUpdate,15);
				$departmentNameUpdate = '';
				$departmentNumUpdate = '';
				$departmentManagerUpdate = '';
				$departmentDetailUpdate = '';
				
				$success = "Update success";
				// header("Refresh:0");
			}
		}
		else{
			$error = "Data is not allowed NULL. Update fail!";
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
	<!-- <link rel="stylesheet" href="/style.css"> Sử dụng link tuyệt đối tính từ root, vì vậy có dấu / đầu tiên -->
	<link rel="stylesheet" href="/style.css">
	<title>Home Page</title>
</head>

<body>
<?php
    if(!empty($error)){
        echo "<div class='alert alert-danger text-center' style='margin-bottom: 0 !important'>$error</div>";
    }
    if(!empty($success)){
        echo "<div class='alert alert-primary text-center' style='margin-bottom: 0 !important'>$success</div>";
    }
?>
	<input type="hidden" id="page" name="page" value="departmentphp">
    <div class="container-fluid admin-section-header">	
        <div class="row">
			<div class="col-sm-12 col-md-10 col-lg-10 col-xl-10 admin-logo">
				Company System
			</div>
			<div class="col-sm-12 col-md-1 col-lg-1 col-xl-1 admin-login-info">

					<a href="account.php">Welcome, <?= $_SESSION['name'] ?></a>
			</div>
			<div class="col-sm-12 col-md-1 col-lg-1 col-xl-1 admin-login-info">

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
							<li class="nav-item ">
								<a class="nav-link p20" href="index.php"><i class="fas fa-user"></i>  Account </a>
							</li>
							<li class="nav-item active-menu">
								<a class="nav-link p20" href="department.php"> <i class="fas fa-building"></i>  Department</a>
							</li>
							<li class="nav-item">
								<a class="nav-link p20" href="dayoff.php"><i class="fas fa-address-book"></i>  Absence Request</a>
							</li>
						</ul>
						</form>
					</div>
				</nav>
			</div>
			<div class="col-md-10 col-lg-10 col-xl-10  ">
				<div class="bg-light mt-4 text-dark p-2" style="width: 100%; overflow-x:scroll">
					<div class="admin-panel-section-header">
						<h2>List Departments</h2>
						<a class="addbtn"  data-toggle="modal" data-target="#add-department">Add Department</a>
					</div>
					<table class="table-hover text-center pc-table"   border="1" >
						<tr class="header">
							<th>ID </th>
							<th>Department Name</th>
							<th>Department Number </th>
							<th>Manager Name</th>
							<th>Detail</th>
							<th>Action</th>
						</tr>
						<tbody id="tbody">
						<?php 
							$result = get_departments(); 
							// $department = '';
							if($result['code'] == 0){
								$data = $result['data'];
								// print_r($data) ;
								foreach($data as $row){
									// $department = $row['name'];
									// print_r($department);
									// print_r($row) ;
									?>
									<tr class="item">
										<td><?= $row['id'] ?></td>
										<td><?= $row['name'] ?></td>
										<td><?= $row['number_room'] ?></td>
										<?php
											if($row['manager_user']){
												?>
													<td>
														<?= $row['manager_user'] ?>
													</td>
												<?php
											}else{
												?>
													<td style="color: red;">
														Click Edit to choose manager
													</td>
												<?php 
											}
										?>
										
										<td><?= $row['detail'] ?></td>
										<td >
											<a 
												class="btn btn-primary bg-primary"
												data-toggle="modal" 
												data-target="#edit-department-detail"
												onclick="handleTransferToUpdate('<?= $row['id'] ?>','<?= $row['name'] ?>','<?= $row['number_room'] ?>','<?= $row['manager_user'] ?>','<?= $row['detail'] ?>')" 
											>
												Edit     
											</a>
											<!-- <a 
												class="btn btn-primary bg-primary"
												href="update_de.php"
											>
												Edit     
											</a> -->
											<a href="#" 
													class="btn btn-danger"
													onclick="handleTransferToDelete('<?= $row['name'] ?>','<?= $row['id'] ?>')" 
													data-toggle="modal" 
													data-target="#delete-department" 
												>Delete</a>
												<a href="departmentDetail.php?id=<?= $row['id']?>" class="btn btn-success">View detail</a>  
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
    <div id="add-department" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <hp class="modal-title">Add Department</hp>
                    <button type="button" class="close" data-dismiss="modal" >&times;</button>
                </div>
                <form id="add-form" method="post" novalidate enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="departmentNameAdd">Department Name</label>
                            <input name="departmentNameAdd" required class="form-control" type="text" placeholder="Department Name" id="departmentNameAdd">
                        </div>
                        <div class="form-group">
                            <label for="departmentNumAdd">Department Number</label>
                            <input name="departmentNumAdd" required class="form-control" type="number" placeholder="Department Number" id="departmentNumAdd">
                        </div>
						<!-- <div class="form-group">
                            <label for="departmentManagerAdd">Manager Name</label>
                            <input name="departmentManagerAdd" required class="form-control" type="text" placeholder="Manager Name" id="departmentManagerAdd">
                        </div> -->
                        <div class="form-group">
                            <label for="departmentDetailAdd">Department Detail</label>
                            <textarea id="departmentDetailAdd" name="departmentDetailAdd" rows="4" class="form-control" placeholder="Department Detail"></textarea>
                        </div>
						
                        <div class="form-group">
							<div id="error-message" style="display:none" class='alert alert-danger'></div>
							<?php
								if (!empty($error)) {
									echo "<div class='alert alert-danger'>$error</div>";
								}
								if (!empty($success)) {
									echo "<div class='alert alert-success'>$success</div>";
								}
							?>
                            <button type="submit" class="btn btn-primary px-5 mr-2">Add</button>
                        </div>
                    </div>
                </form>
            </div>  
        </div>
    </div>

	<!-- Delete Confirm Modal -->
    <div id="delete-department" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <hp class="modal-title">Delete Department</hp>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                
                <div class="modal-body">
                    <p>Are you sure want to delete department <strong class="department-delete-name">Home</strong> ?</p>
                </div>
                <div class="modal-footer">
                    <input type="hidden">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button id="btn-del" type="submit" class="btn btn-danger"  class="btn-delete-modal" data-dismiss="modal">Xóa</button>
                    
                </div>
            </div>
        </div>
    </div>

	<!-- Edit Confirm Modal -->
	<div id="edit-department-detail" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<hp class="modal-title">Edit infomation employee</hp>
					<button type="button" class="close close-edit" data-dismiss="modal" >&times;</button>
				</div>
				<form method="post" id="update-form" novalidate enctype="multipart/form-data">
					<div class="modal-body">
						<div class="form-group">
                            <label for="departmentNameUpdate">Department Name</label>
                            <input readonly name="departmentNameUpdate" required class="form-control" type="text" placeholder="Department Name" id="departmentNameUpdate">
							
                        </div>
                        <div class="form-group">
                            <label for="departmentNumUpdate">Department Number</label>
                            <input name="departmentNumUpdate" required class="form-control" type="number" placeholder="Department Number" id="departmentNumUpdate">
                        </div>
						<div class="form-group">
                            <label for="departmentManagerUpdate">Manager Name</label>
							<select id="departmentManagerUpdate" class="form-control" name="departmentManagerUpdate" required>
								<option value="" disabled selected>Manager Name</option>
								
							</select>
                        </div>
                        <div class="form-group">
                            <label for="departmentDetailUpdate">Department Detail</label>
                            <textarea id="departmentDetailUpdate" name="departmentDetailUpdate" rows="4" class="form-control" placeholder="Department Detail"></textarea>
                        </div>
						<div class="form-group">
							<div id="err-message" style="display:none" class='alert alert-danger'></div>
							<button id="edit-btn" type="submit" class="btn btn-primary px-5 mr-2">Edit</button>
						</div>
					</div>
					<input id="edit-hidden" type="hidden" name="is_edit" value="0">
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
		//thêm
		const addForm = document.querySelector('#add-form')
		const errorMess = document.getElementById('error-message')
        addForm.addEventListener('submit', async (e)=>{
            e.preventDefault();
            const departmentNameAdd = document.querySelector('#departmentNameAdd').value
            const departmentNumAdd = document.querySelector('#departmentNumAdd').value
			const departmentDetailAdd = document.querySelector('#departmentDetailAdd').value
            
			if(departmentDeleteName === ''){
				errorMess.style.display = 'block';
				errorMess.innerHTML = 'Please enter department name';
			}
			else if(departmentNumAdd === ''){
				errorMess.style.display = 'block';
				errorMess.innerHTML = 'Please enter department number';
			}
			else if(departmentDetailAdd === ''){
				errorMess.style.display = 'block';
				errorMess.innerHTML = 'Please enter department detail';
			}

			const sendRequest = await fetch('add_department.php',{
                method: 'POST',
                body: JSON.stringify({departmentNameAdd,departmentNumAdd,departmentDetailAdd})
            })
            const res = await sendRequest.json();
            reloadPage(res)
        })

		//xóa
		let currentID;
        const departmentDeleteName = document.querySelector('.department-delete-name');

        function handleTransferToDelete(name, id){
            departmentDeleteName.innerHTML = name;
            currentID = id;
        }

		document.getElementById('btn-del').addEventListener('click',async () =>{
            const request = await fetch('delete_department.php',{
                method: 'delete',
                body: JSON.stringify({id:currentID}),
                headers: {
                    "Content-Type": "application/json"
                },
            })
            const res = await request.json();
            reloadPage(res)
        })

        //update
		const select = document.getElementById('departmentManagerUpdate');

		document.querySelector('.close-edit').addEventListener('click',() => {
			select.innerHTML = '';
		})

        function handleTransferToUpdate(id,name,number,manager,detail){
			currentID = id;
            document.querySelector('#departmentNameUpdate').value = name;
            document.querySelector('#departmentNumUpdate').value = number;
            // console.log(document.querySelector('#departmentManagerUpdate').value);
            document.querySelector('#departmentDetailUpdate').innerHTML = detail;
			const departmentName = document.querySelector('#departmentNameUpdate').value;
			(async () => {
				// select.insertAdjacentHTML('beforeend','<option value="" disabled selected>Manager Name</option>');
				const departmentName = document.querySelector('#departmentNameUpdate').value;
				
				const request1 = await fetch(`get_manager_name.php?department=${departmentName}`);
				const res = await request1.json();
				let optionSelectDeparment;
				if(res['code']){
					select.innerHTML = '';
					optionSelectDeparment = `<option value="" disabled selected>${res['error']}</option>`;
				}else{

					const data = res['data'];
					optionSelectDeparment = data.map(e => `
						<option value="${e}">${e}</option>		
					`).join('')
					// console.log(optionSelectDeparment.value);
				}

				select.insertAdjacentHTML('beforeend',optionSelectDeparment);
			})()
        }
		// const errMess = document.getElementById('err-message');
        // document.querySelector('#update-form').addEventListener('submit',async (e)=>{
        //     e.preventDefault();
        //     const departmentNameUpdate = document.querySelector('#departmentNameUpdate').value
        //     const departmentNumUpdate = document.querySelector('#departmentNumUpdate').value
        //     const departmentManagerUpdate = document.querySelector('#departmentManagerUpdate').value
		// 	console.log(departmentManagerUpdate);
        //     const departmentDetailUpdate = document.querySelector('#departmentDetailUpdate').value
		// 	if(departmentNumUpdate === ''){
		// 		errMess.style.display = 'block';
		// 		errMess.innerHTML = 'Please enter department number';
		// 	}
		// 	else if(departmentDetailUpdate === ''){
		// 		errMess.style.display = 'block';
		// 		errMess.innerHTML = 'Please enter department detail';
		// 	}

		// 	const sendRequest = await fetch('update_department.php',{
		// 		method: 'POST',
		// 		body: JSON.stringify({id:currentID,departmentNameUpdate,departmentNumUpdate,departmentManagerUpdate,departmentDetailUpdate})
		// 	})
		// 	const res = await sendRequest.json();
		// 	if(res['code']){
		// 		// errMess.style.display = 'block';
		// 		// errMess.innerHTML = res['message'];
		// 	}else{
		// 		// console.log(res);
		// 		select.innerHTML = '';
		// 		reloadPage(res);
		// 	}
        // })

		document.getElementById('edit-btn').addEventListener('click',() => {
			document.getElementById('edit-hidden').value = 1;
		})
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
