<?php
    ob_start();
    require_once('db.php');
    // if(get_info_employee_byuser($_SESSION['user'])['role'] != 'employee' ){
    //     move_page(get_info_employee_byuser($_SESSION['user'])['role']);
    //     exit();
    // }

    if(isset($_GET['id'])){
        $id = $_GET['id'];
        echo $id;
        $sql = "update task set status = 'In progress' where id=?";
        $conn = open_database();

        $stm = $conn->prepare($sql);
        $stm->bind_param('i',$id);
        if(!$stm->execute()){
            return json_encode(array('code'=> 2, 'error' => 'Can not execute command.'));
        }
        update_modified_time($id);
        header("Location: task_detail.php?id=$id");
    }else{
        return json_encode(array('code'=> 1,'success' => 'Only can use GET method'));
        // return json_encode(array('code'=> 1,'error' => 'Only can use GET method'));
    }
    ob_end_flush();
?>
