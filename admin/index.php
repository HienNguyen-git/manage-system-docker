<?php
	require_once('db.php');
    session_start();
	$user = get_info_employee_byuser($_SESSION['user']);
    if (!isset($_SESSION['user'])) {
        header('Location: ../login.php');
        exit();
    }
	else if($user['role'] != 'admin' ){
        move_page($user['role']);
        exit();
		
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
	<input type="hidden" id="page" name="page" value="indexphp">
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
							<li class="nav-item active-menu">
								<a class="nav-link p20" href="index.php"><i class="fas fa-user"></i>  Account </a>
							</li>
							<li class="nav-item ">
								<a class="nav-link p20" href="department.php"> <i class="fas fa-building"></i>  Department</a>
							</li>
							<li class="nav-item">
								<a class="nav-link p20" href="dayoff.php"><i class="fas fa-address-book"></i>  Absence Request</a>
							</li>
						</ul>
					</div>
				</nav>
			</div>
			<div class="col-md-10 col-lg-10 col-xl-10   " >
				<div class="bg-light mt-4 text-dark p-2 " style="width: 100%; overflow-x:scroll">
					<div class="admin-panel-section-header ">
						<h2>List Account</h2>
						<a href="register.php" class="addbtn" >Add Accout</a>
					</div>
					<table class="table-hover text-center pc-table"   border="1" >
						<tr class="header">
							<th>ID</th>
							<th>Username</th>
							<th>Department Name</th>
							<th>Action</th>
						</tr>
						<tbody id="tbody">
							<?php 
								$result = get_info_employees(); 
								if($result['code'] == 0){
									$data = $result['data'];
									foreach($data as $row){
										// print_r($row['id']) ;
										if($row['username'] != 'admin'){

										?>
										<tr class="item">
											<td><?= $row['id'] ?></td>
											<td><?= $row['username'] ?></td>
											<td><?= $row['department'] ?></td>
											<!-- <td class="search-td" style="position: absolute; border: none;">
												<div >
													<input  oninput="suggest(this.value)" type="text" class="form-control input-search-employee" placeholder="Nhập ít nhất 2 ký tự">
													<ul style="place-items: flex-start;" id="suggestions" class="list-group my-2">
														<li class="list-group-item">Vietnam</li>
												
													</ul>
												</div>
												<a  href="#" class="btn btn-success">Update</a>
											</td> -->
											<td >
												
												<a href="#" 
													class="btn btn-danger"
													onclick="handleTransferToDelete('<?= $row['username'] ?>','<?= $row['id'] ?>')" 
													data-toggle="modal" 
													data-target="#delete-employee" 
												>Delete </a> 
												<a href="detailEmployee.php?id=<?=$row['id']?>" class="btn btn-success">Detail</a>
											</td>
										</tr>
										<?php
										}
									}
								}
							?>
						</tbody>
					</table>
				</div>
			</div>		
		</div>
		
	</div>

	<!-- Delete Confirm Modal -->
    <div id="delete-employee" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <hp class="modal-title">Delete User</hp>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                
                <div class="modal-body">
                    <p>Are you sure want to delete user <strong class="employee-delete-name">lelong</strong> ?</p>
                </div>
                <div class="modal-footer">
                    <input type="hidden">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button id="btn-del" type="submit" class="btn btn-danger"  class="btn-delete-modal" data-dismiss="modal">Xóa</button>
                    
                </div>
            </div>
        </div>
    </div>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	<!-- <script src="/main.js"></script> Sử dụng link tuyệt đối tính từ root, vì vậy có dấu / đầu tiên -->
	<script src="../main.js"></script> 

	<!-- <script>
        function suggest(value) {
			$.get(
			"searchEmployee.php?search-text=" + value,
			(data) => {
				$("#suggestions li").remove();
				data.forEach((country) => {
				let item = `<li onclick="handleClickLi(this)" class="list-group-item" style="cursor: pointer;">${capitalizeFirstLetter(
					country
				)}</li>`;
				$("#suggestions").append(item);
				});
			},
			"json"
			);
		}
		function capitalizeFirstLetter(string) {
			return string.charAt(0).toUpperCase() + string.slice(1);
		}

		let inputSearch = document.querySelector('.input-search-employee')
		let listGroupItem = document.querySelectorAll('.list-group-item');
		
		function handleClickLi(nameitem){
			inputSearch.value = nameitem.innerHTML;
			$("#suggestions li").remove();
		}
		
		//xóa
		let currentID;
        const employeeDeleteName = document.querySelector('.employee-delete-name');
        function handleTransferToDelete(name, id){
            employeeDeleteName.innerHTML = name;
            currentID = id;
        }
		document.getElementById('btn-del').addEventListener('click',async () =>{
            const request = await fetch('delete_employee.php',{
                method: 'delete',
                body: JSON.stringify({id:currentID}),
                headers: {
                    "Content-Type": "application/json"
                },
            })
            
            const res = await request.json();
			
			
            reloadPage(res);
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
