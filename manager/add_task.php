<?php 
    require_once('../admin/db.php');
    session_start();
    $user =get_info_employee_byuser($_SESSION['user']);
    if (!isset($_SESSION['user'])) {
        header('Location: ../login.php');
        exit();
    }
    //save department
	$department = get_department_byuser($_SESSION['user'])['department'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Add new task</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</head>
<body class = "bg-info">

<input type="hidden" name="page" id="page" value="manager-addtask">
<?php

    $error = '';
    $success = '';

    $taskTitleAdd = '';
    $taskDetailAdd = '';
    $deadlineAdd = '';
    $taskEmployeeAdd = '';

    if (isset($_POST['taskTitleAdd']) || isset($_POST['taskDetailAdd']) || isset($_POST['deadlineAdd']) || isset($_POST['taskEmployeeAdd']) || isset($_FILES['file']))
    {
        $taskTitleAdd = $_POST['taskTitleAdd'];
        $taskDetailAdd = $_POST['taskDetailAdd'];
        $deadlineAdd = $_POST['deadlineAdd'];
        $taskEmployeeAdd = isset($_POST['taskEmployeeAdd']) ? $_POST['taskEmployeeAdd'] : '';

        $file = $_FILES['file'];
		$file_name = $file['name'];
		$file_size = $file['size'];
		$file_tmp = $file['tmp_name'];
		$tmp = explode('.', $file_name);
		$file_ext = strtolower(end($tmp));
		$extensions= array("txt","doc","docx","xls","xlsx","jpg","png","mp3","mp4","pdf","rar","zip","pptx","html","sql","ppt","jpeg");

        if (empty($taskTitleAdd)) {
            $error = 'Please enter title task';
        }
        else if (empty($taskDetailAdd)) {
            $error = 'Please enter detail task';
        }
        else if (empty($deadlineAdd)) {
            $error = 'Please enter deadline task';
        }
        else if (empty($taskEmployeeAdd)) {
            $error = 'Please enter person do task';
        }
        else if(!$file_name){
			$error = "Please upload file task";
		}
        else if(!in_array($file_ext,$extensions)){
			$error = "This type of file is not allowed";
		}
        else if($file_size > 104857600){
			$error = "This file is larger than 100M";
		}
        else{
			$file_path = "../upload/".$file_name;
			move_uploaded_file($file_tmp,$file_path);
            $file_path_name = "upload/".$file_name;

			$success ="submit successful";
			$result = add_task($taskTitleAdd,$taskDetailAdd,$taskEmployeeAdd,$deadlineAdd,$file_path_name);
            if($result['code'] == 0){
                $taskTitleAdd = '';
                $taskDetailAdd = '';
                $deadlineAdd = '';
                $success = $result['success'];
            }
            else{
                $error = $result['error'];
            }
        }
    }
    
?>
    <div class="container ">
        <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-6 col-md-8 border rounded my-5 p-4  mx-3 bg-light">
                <p class="mb-5"><a href="index.php">Back</a></p>
                <h3 class="text-center text-secondary mt-2 mb-3 mb-3">Add New Task</h3>
                <form method="post" action="" novalidate enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="taskTitleAdd">Title Task</label>
                        <input value ="<?= $taskTitleAdd ?>" name="taskTitleAdd" required class="form-control" type="text" placeholder="Title Task " id="taskTitleAdd">
                    </div>
                    <div class="form-group">
							<label for="taskDetailAdd">Detail Task</label>
							<textarea id="taskDetailAdd" name="taskDetailAdd" rows="4" class="form-control" placeholder="Detail Task"><?= $taskDetailAdd ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="deadlineAdd">Deadline</label>
                        <input name="deadlineAdd"  value ="<?= $deadlineAdd ?>" required class="form-control" type="date" id="deadlineAdd" min='1899-01-01' max='2025-10-10'>
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
                            <option value="<?= $row['username'] ?>" disabled selected>Employee Name</option>
                            <?php 
                                $result = get_employee_bydepartment($department);
                                print_r($result);
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
                    <div class="form-group">
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
                </form>

            </div>
        </div>

    </div>
    <script src="../main.js"></script>
<!-- <script>
    // Add the following code if you want the name of the file appear on select
    $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });
</script>
<script>
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1; //January is 0!
    var yyyy = today.getFullYear();

    if (dd < 10) {
    dd = '0' + dd;
    }

    if (mm < 10) {
    mm = '0' + mm;
    } 
        
    today = yyyy + '-' + mm + '-' + dd;
    document.getElementById("deadlineAdd").setAttribute("min", today);
</script> -->
</body>
</html>

