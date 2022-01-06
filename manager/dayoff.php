<?php
	session_start();
	ob_start();
    require_once('../admin/db.php');
    if (!isset($_SESSION['user'])) {
        header('Location: login.php');
        exit();
    }
	$department = get_department_byuser($_SESSION['user'])['department'];
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

    <div class="container-fluid admin-section-header">	
        <div class="row">
			<div class="col-sm-12 col-md-10 col-lg-10 col-xl-10 admin-logo">
			Company System | <?=$department?>
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
				<nav class="  navbar-expand-lg navbar-light  " >
					<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
						<span class="navbar-toggler-icon"></span>
					</button>
		
					<div class="collapse navbar-collapse" id="navbarSupportedContent">
						<ul class="navbar-nav" style="flex-direction: column;">
							<li class="nav-item ">
								<a class="nav-link p20" href="index.php"><i class="fas fa-tasks"></i>List Task</a>
							</li>
							
							<li class="nav-item active-menu">
								<a class="nav-link p20" href="dayoff.php"><i class="fas fa-address-book"></i>  Absence Request</a>
							</li>
							<li class="nav-item">
								<a class="nav-link p20" href="absence.php"><i class="fas fa-address-book"></i>  Absence </a>
							</li>
						</ul>
					</div>
				</nav>
			</div>
			<div class="col-md-10 col-lg-10 col-xl-10  ">
				<div class="bg-light mt-4 text-dark p-2" style="width: 100%; overflow-x:scroll">
					<div class="admin-panel-section-header">
						<h2>List Absence Request</h2>
					</div>
					<table class="table-hover" cellpadding="10" cellspacing="10" border="1" style="width: 100%; margin-top:20px">
						<tr class="header">
							<td>ID</td>
							<td>Full Name</td>
							<td>Day created</td>
							<td>Status</td>
							<td>Action</td>
						</tr>
						<tbody id="tbody">
						<?php 
							$result = get_absence_by_role('employee',$department); 
							if($result['code'] == 0){
								$data = $result['data'];
								foreach($data as $row){
									?>
									<tr class="item">
										<td><?= $row['id']?></td>
										<td><?= $row['username']?></td>
										<td><?= $row['create_date']?></td>
										<?= status_ui($row['status'])?>
										<td ><a href="dayoffDetail.php?id=<?= $row['id']?>" class="btn btn-success">View detail</a> </td> 
										
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

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
<?php
    ob_end_flush();
?>