<?php
    require_once('../admin/db.php');
    session_start();
    if (!isset($_SESSION['user'])) {
        header('Location: login.php');
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
	<link rel="stylesheet" href="../style.css">
	<title>Home Page</title>
</head>

<body>
    <?php
    /*
    $link = mysqli_connect("localhost", "root", "", "");
    $sql = "SELECT * FROM account where username='$user'";
    $result = mysqli_fetch_assoc(mysqli_query($link, $sql));
    // print_r($result);

    $username = $result['username'];
    $firstname = $result['firstname'];
    $lastname = $result['lastname'];
    $email = $result['email'];
    $sdt = $result['sdt'];
    */
    ?>
    <div class="container-fluid admin-section-header">	
        <div class="row">
			<div class="col-sm-10 col-md-10 col-lg-10 col-xl-10 admin-logo">

					Company System
			</div>
			<div class="col-sm-1 col-md-1 col-lg-1 col-xl-1 admin-login-info">

					<a href="#">Welcome, <?= $_SESSION['name'] ?></a>
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
							<li class="nav-item">
								<a class="nav-link p20" href="./"><i class="fas fa-tasks"></i>List Task</a>
							</li>
							<li class="nav-item active-menu">
								<a class="nav-link p20" href="task_submit.php"><i class="fas fa-tasks"></i>Task Submit</a>
							</li>
							<li class="nav-item">
								<a class="nav-link p20" href="dayoff.php"><i class="fas fa-address-book"></i>  Absence Request</a>
							</li>
						</ul>
					</div>
				</nav>
			</div>
			<div class="col-md-10 col-lg-10 col-xl-10 ">
				<div class="bg-light mt-4 text-dark p-2">
                    <div class="admin-panel-section-header ">
                        <h2>Submit detail </h2>
                        <!-- <a class="addbtn"  data-toggle="modal" data-target="#add-movie">Add Accout</a> -->
                    </div>
                    <div class="account-container">
                        <table class="table-hover">
                            <?php 
                                $id = $_GET['id'];
                                $result = get_task_detail($id); 
                                if($result['code'] == 0){
                                    $data = $result['data'];
                                    ?>    
                                        <tr>
                                            <th>ID Task</th>
                                            <td><?= $data['id']?></td>
                                        </tr>
                                        <tr>
                                            <th>Title</th>
                                            <td><?= $data['title']?></td>
                                        </tr>
                                        <tr>
                                            <th>Description</th>
                                            <td><?= $data['description']?></td>
                                        </tr>
                                        <tr>
                                            <th>Person</th>
                                            <td><?= $data['person']?></td>
                                        </tr>
                                        <tr>
                                            <th>Deadline</th>
                                            <td><?= $data['deadline']?></td>
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
                                            <?=status_ui($data['status'])  ?></td>
                                        </tr>
                                    <?php 
                                        if($data['status'] == 'Waiting'){
                                            ?>
                                                <tr>
                                                    <td>Action</td>
                                                    <td >
                                                        <a href="update_status_absence.php?id=<?=$id?>&username=<?=$username?>" class="btn btn-primary">Complete</a> | 
                                                        <a href="refused_absence.php?id=<?=$id?>&username=<?=$username?>" class="btn btn-danger">Reject</a>
                                                    </td>
                                                </tr>
                                            <?php
                                        }
                                }
                            ?>
                        </table>
                    </div>
				</div>
			</div>		
		</div>
	</div>

	

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	<!-- <script src="/main.js"></script> Sử dụng link tuyệt đối tính từ root, vì vậy có dấu / đầu tiên -->
	<!-- <script src="main.js"></script> Sử dụng link tuyệt đối tính từ root, vì vậy có dấu / đầu tiên -->
</body>

</html>
