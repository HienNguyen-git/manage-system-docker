<?php 
    ob_start();
    require_once('../admin/db.php');

    session_start();
    if (!isset($_SESSION['user'])) {
        header('Location: /login.php');
        exit();
    }else if(get_info_employee_byuser($_SESSION['user'])['role'] != 'manager' ){
        move_page_manager(get_info_employee_byuser($_SESSION['user'])['role']);
        exit();
    }
	function move_page_manager($role){
        if($role == 'employee'){
			header('Location: ../index.php');
		}
		else if($role == 'manager'){
			header('Location: .index.php');
		}
		else{
			header('Location: ../admin/index.php');
		}
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
        update_approval_date($user);
        header("Location: dayoffDetail.php?id=$id");
    }
    ob_end_flush();
?>