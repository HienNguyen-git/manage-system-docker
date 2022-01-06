<?php
    session_start();
    ob_start();
    require_once('db.php');
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
	<link rel="stylesheet" href="/style.css">
	<title>Home Page</title>
</head>

<body>
    <input type="hidden" id="page" name="page" value="detailEmployeephp">
    <div class="container-fluid admin-section-header">	
        <div class="row">
			<div class="col-sm-10 col-md-10 col-lg-10 col-xl-10 admin-logo">
			    Company System
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
			<div class="col-md-10 col-lg-10 col-xl-10 ">
				<div class="bg-light mt-4 text-dark p-2">
                    <div class="admin-panel-section-header ">
                        <h2>Detail Employee </h2>
                        <a class="addbtn btn " style="background-color: black;" href="index.php">Back</a>

                        <!-- <a class="addbtn"  data-toggle="modal" data-target="#add-movie">Add Accout</a> -->
                    </div>
                    <div class="account-container" style="position: relative;">
                        
                        <?php
                            $username = '';
                            $firstname = '';
                            $lastname = '';
                            $role = '';
                            $department = '';
                            $id = $_GET['id'];
                            $result = get_info_employee_byid($id);
                            if($result['code'] == 0){
                                $data = $result['data'];
                                foreach($data as $row){
                                    // echo $row['avatar'];
                                    // die();
                                    $username = $row['username'];
                                    $firstname = $row['firstname'];
                                    $lastname = $row['lastname'];
                                    $role = $row['role'];
                                    $department = $row['department'];
                                    // $img = $row['avatar']
                                    ?>
                                        <div class="image-box">
                                                <img src="../<?= $row['avatar'] ?>" alt="Avatar">
                                            
                                        </div>
                                        <table class="table-hover">
                                            <tr>
                                                <td>ID</td>
                                                <td><?= $row['id']?></td>
                                            </tr>
                                            <tr>
                                                <td>Username</td>
                                                <td><?= $row['username']?></td>
                                            </tr>
                                            <tr>
                                                <td>FirstName</td>
                                                <td><?= $row['firstname']?></td>
                                            </tr>
                                            <tr>
                                                <td>LastName</td>
                                                <td><?= $row['lastname']?></td>
                                            </tr>
                                            <tr>
                                                <td>Role</td>
                                                <td><?= $row['role']?></td>
                                            </tr>
                                            <tr>
                                                <td>Department</td>
                                                <td><?= $row['department']?></td>
                                            </tr>
                                            <tr>
                                                <td>

                                                    <a id="btnReset" 
                                                        class="btn btn-info bg-info"
                                                        data-toggle="modal" 
                                                        data-target="#reset-modal"
                                                        onclick="handleTransferToReset('<?= $username ?>')" 
                                                    >
                                                        Reset Password      
                                                    </a>
                                                </td>
                                                <td>
                                                    <a 
                                                        style="min-width: 145px;"
                                                        class="btn btn-primary bg-primary"
                                                        data-toggle="modal" 
                                                        data-target="#edit-employee-detail"
                                                        onclick="handleTransferToUpdate('<?= $id ?>','<?= $firstname ?>','<?= $lastname ?>')" 
                                                    >
                                                        Edit     
                                                    </a>

                                                </td>
                                            </tr>
                                        </table>
                                        
                                    <?php
                                }
                            }  
                        ?>
                        <!-- Reset Modal -->
                        <div id="reset-modal" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <hp class="modal-title">Reset Password</hp>
                                        <button type="button" class="close" data-dismiss="modal" >&times;</button>
                                    </div>
                                    
                                        <div class="modal-body">
                                            <div class="modal-body">
                                                <p>Are you sure reset password <strong class="name-resetpass"></strong> ?</p>
                                            </div>
                                            <div class="form-group">
                                                <div id="succmessage" style="display: none;" class="alert alert-success"></div>
                                                <a id="reset-btn" href="reset_password.php?username=<?=$username?>&id=<?=$id?>" class="btn btn-primary px-5 mr-2" >Reset</a>
                                            </div>
                                        </div>
                                </div>  
                            </div>
                        </div>
                        
                        <!-- Edit Confirm Modal -->
                        <div id="edit-employee-detail" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <hp class="modal-title">Edit infomation employee</hp>
                                        <button type="button" class="close" data-dismiss="modal" >&times;</button>
                                    </div>
                                    <form method="post" id="update-form" novalidate enctype="multipart/form-data">
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="FirstNameUpdate">FirstName</label>
                                                <input name="firstName" required class="form-control" type="text" placeholder="FirstName" id="firstNameUpdate">
                                            </div>
                                            <div class="form-group">
                                                <label for="LastNameUpdate">LastName</label>
                                                <input name="lastName" required class="form-control" type="text" placeholder="LastName" id="lastNameUpdate">
                                            </div>
                                            <!-- <div class="form-group">
                                                <label for="RoleUpdate">Role</label>
                                                <input name="role" required class="form-control" type="text" placeholder="Role" id="roleUpdate">
                                            </div> -->
                                            <div class="form-group">
                                                <div id="error-message" style="display: none !important;" class="alert alert-danger"></div>
                                                <div id="success-message" style="display: none !important;" class="alert alert-success"></div>
                                                <button type="submit" class="btn btn-primary px-5 mr-2">Edit</button>
                                            </div>
                                        </div>
                                    </form>
                                    
                                    
                                </div>  
                            </div>
                        </div>
                    </div>
				</div>
                
            </div>		
		</div>
	</div>

	

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	<!-- <script src="/main.js"></script> Sử dụng link tuyệt đối tính từ root, vì vậy có dấu / đầu tiên -->
	<script src="/main.js"></script> 

    <!-- <script>
        const nameResetpass = document.querySelector('.name-resetpass');
        let currentUsernme;
        function handleTransferToReset(name){
            nameResetpass.innerHTML = name;
            currentUsernme = name;
        }
        
        let currentID
        //update
        function handleTransferToUpdate(id,firstname,lastname){
            // console.log(id,firstname,lastname,role);
            currentID = id;
            document.querySelector('#firstNameUpdate').value = firstname;
            document.querySelector('#lastNameUpdate').value = lastname
            // document.querySelector('#roleUpdate').value = role
        }

        document.querySelector('#update-form').addEventListener('submit',async (e)=>{
            e.preventDefault();
            const firstname = document.querySelector('#firstNameUpdate').value
            const lastname = document.querySelector('#lastNameUpdate').value
            // const role = document.querySelector('#roleUpdate').value
            const errorMessage = document.getElementById('error-message');
            const successMessage = document.getElementById('success-message');

            if(firstname === ''){
                errorMessage.style.display = "block";
                successMessage.style.display = "none !important";
                errorMessage.innerHTML = 'Please enter firstname of this person';
            }else if(lastname === ''){
                errorMessage.style.display = "block";
                successMessage.style.display = "none !important";
                errorMessage.innerHTML = 'Please enter lastname of this person';
            }else{
                errorMessage.style.display = "none";
                successMessage.style.display = "block";
                successMessage.innerHTML = 'Update success';
            }

            const sendRequest = await fetch('update_employee.php',{
                method: 'POST',
                body: JSON.stringify({id:currentID,firstname,lastname})
            })

            const res = await sendRequest.json();
            
            
            $('#success-message').addClass("d-block");
			setTimeout(function() {
                if(res.code===0){
                    location.reload();
                }
			}, 500);

            // if(res['code']){ //code khác 0 là lỗi
            //     // errorMessage.style.display = "block";
            //     // errorMessage.innerHTML = res['message'];
            // }
            // reloadPage(res)
        })

        //reset pass
        document.getElementById('reset-btn').addEventListener('click', () => {
            const successMessage = document.getElementById('succmessage');
            successMessage.style.display = "block";
                successMessage.innerHTML = 'Reset password success';
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
<?php
    ob_end_flush();
?>