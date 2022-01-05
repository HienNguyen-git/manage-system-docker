<?php
    session_start();
    require_once('db.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Register an account</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    
</head>
<body style="background-color: #ccc;">
<?php
    $success = '';
    $error = '';
    $first_name = '';
    $last_name = '';
    $user = '';
    $department = '';

    if (isset($_POST['first']) || isset($_POST['last'])
    || isset($_POST['user'])  )
    {
        $first_name = $_POST['first'];
        $last_name = $_POST['last'];
        $user = $_POST['user'];
        $department = isset($_POST['department']) ? $_POST['department'] : '';

        if (empty($first_name)) {
            $error = 'Please enter your first name';
        }
        else if (empty($last_name)) {
            $error = 'Please enter your last name';
        }
        else if (empty($user)) {
            $error = 'Please enter your username';
        }
        else if (strlen($user) < 6) {
            $error = 'Username must have at least 6 characters';
        }
        else if (empty($department)) {
            $error = 'Please select department';
        }
        else {
            // register a new account
            // echo 'good';
            $result = register($user,$first_name,$last_name,$department);
            if($result['code'] == 0){
                $success = $result['success'];
                add_absence_info($user);
                $first_name = '';
                $last_name = '';
                $user = '';
                $department = '';
            }else if($result['code'] == 1){ //trùng username
                $error = $result['error'];
            }else{
                $error = $result['error'];
            }

        }
    }
?>
    <div class="container" >
        <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-6 col-md-8 border my-5 p-4 rounded mx-3 bg-info">
                <h3 class="text-center text-secondary mt-2 mb-3 mb-3">Create a new account</h3>
                <form method="post" action="" novalidate enctype="multipart/form-data">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="firstname">First name</label>
                            <input value="<?= $first_name?>" name="first" required class="form-control" type="text" placeholder="First name" id="firstname" autofocus>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="lastname">Last name</label>
                            <input value="<?= $last_name?>" name="last" required class="form-control" type="text" placeholder="Last name" id="lastname">
                            <div class="invalid-tooltip">Last name is required</div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="user">Username</label>
                        <input value="<?= $user?>" name="user" required class="form-control" type="text" placeholder="Username" id="user">
                        <div class="invalid-feedback">Please enter your username</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="department">Department</label>
                        <select name="department" class="form-control" id="department">
                        <option value="" disabled selected>Please select department</option>
                        <?php
                            $result = get_department_name_list();
                            if(!$result['code']){
                                $department_list = $result['data'];
                                foreach ($department_list as $row){
                                    $name = $row['name'];
                                    if($department==$name){
                                        echo "<option value='$name' selected>$name</option>";
                                    }else{
                                        echo "<option value='$name'>$name</option>";
                                    }
                                }
                            }
                        ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <?php
                            if (!empty($error)) {
                                echo "<div id='error-mess' class='alert alert-danger'>$error</div>";
                            }
                            if (!empty($success)) {
                                echo "<div class='alert alert-success'>$success</div>";
                            }
                        ?>
                        <button  type="submit" class="btn btn-success px-5 mt-3 mr-2" style="width: 100%;">Register</button>
                    </div>
                    <div class="form-group">
                        <a href="../login.php" class="btn btn-secondary" >Back</a>
                    </div>
                </form>
            </div>
        </div>

    </div>
    
</body>

</html>

