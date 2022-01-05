<?php
 	session_start();
	require_once('db.php');

	
	if(!isset($_SESSION['user'])){
		header('Location: login.php');
	}
	$user = get_info_employee_byuser($_SESSION['user']);
	
	if( !is_password_changed($_SESSION['user']) ){
		header('Location: change_password.php');
	}
	else if($user['role'] != 'employee' ){
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
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	<!-- <link rel="stylesheet" href="/style.css"> Sử dụng link tuyệt đối tính từ root, vì vậy có dấu / đầu tiên -->
	<link rel="stylesheet" href="/style.css"> <!-- Change -->
	<title>Home Page</title>
	
</head>

<body>
<?php
	include_once('layout/header.php');
?>

<input type="hidden" name="index" id="page" value="index">

<div class="container pb-2" style="height: 70vh;">
		<h1 class="mt-3 mb-3 pb-3 border-bottom border-info text-secondary">WELCOME <?=$_SESSION['user']?></h1>
		<table class="table table-bordered table-light table-hover text-center ali" style="border-color:black;">
			<thead>
				<tr>
					<th scope="col">#</th>
					<th scope="col">Title</th>
					<th scope="col">Deadline</th>
					<th scope="col">Status</th>
					<th scope="col">Action</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$data = get_tasks($_SESSION['user']);

				if($data['code']==0){
					foreach($data['data'] as $row){
						if($row['status']==="Canceled") continue;
					?>
						<tr>
							<th scope="row"><?=$row['id']?></th>
							<td><?=$row['title']?></td>
							<td><?=$row['deadline']?></td>
							<?php
								status_ui($row['status']);
							?>
							<td><a href="task_detail.php?id=<?=$row['id']?>" class="btn btn-primary"><i class="fas fa-eye"></i> View detail</a></td>
						</tr>	
				 	<?php
					}
				}
				?>
			</tbody>
		</table>
	</div>

	<!-- <script src="/main.js"></script> Sử dụng link tuyệt đối tính từ root, vì vậy có dấu / đầu tiên -->
	<script src="/main.js"></script> <!-- Sử dụng link tuyệt đối tính từ root, vì vậy có dấu / đầu tiên -->
</body>

</html>
