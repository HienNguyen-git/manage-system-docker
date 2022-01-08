<?php 
    session_start();
    ob_start();
    require_once('../admin/db.php');
    if (!isset($_SESSION['user'])) {
        header('Location: /login.php');
        exit();
    }else if(get_info_employee_byuser($_SESSION['user'])['role'] != 'admin' ){
        move_page(get_info_employee_byuser($_SESSION['user'])['role']);
        exit();
    }
    if(isset($_GET['id'])&&isset($_GET['username'])){
        $id = $_GET['id'];
        $user = $_GET['username'];

        $sql = "update absence_form set status = 'Refused' where id = ?";
        $conn = open_database();
    
        $stm= $conn->prepare($sql);
        $stm->bind_param('i',$id);
    
        if(!$stm->execute()){
            return array('code' => 2, 'error' => 'Cant execute command');
        }
        update_approval_date($id);
        header("Location: dayoffDetail.php?id=$id");
        // return array('code' => 0, 'success' => 'Password reset');
    }
    ob_end_flush();
?>