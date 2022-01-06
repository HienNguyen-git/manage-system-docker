<?php 
    session_start();
    ob_start();
    require_once('db.php');
    if (!isset($_SESSION['user'])) {
        header('Location: /login.php');
        exit();
    }else if(get_info_employee_byuser($_SESSION['user'])['role'] != 'admin' ){
        move_page(get_info_employee_byuser($_SESSION['user'])['role']);
        exit();
    }
    if(isset($_GET['username']) && isset($_GET['id'])){
        $user = $_GET['username'];
        $id = $_GET['id'];

        $hash = password_hash($user,PASSWORD_DEFAULT);
        $sql = 'update employee set activated = 0, password = ? where username = ?';
        $conn = open_database();
    
        $stm= $conn->prepare($sql);
        $stm->bind_param('ss',$hash,$user);
    
        if(!$stm->execute()){
            return array('code' => 2, 'error' => 'Cant execute command');
        }
        header("Location: detailEmployee.php?id=$id");
    }
    ob_end_flush(); 
?>