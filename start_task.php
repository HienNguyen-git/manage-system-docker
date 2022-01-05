<?php
    require_once('db.php');
    header('Access-Control-Allow-Origin: *');

    header('Access-Control-Allow-Methods: GET, POST');
    
    header("Access-Control-Allow-Headers: X-Requested-With");

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
        header("Location: task_detail.php?id=$id");
    }else{
        return json_encode(array('code'=> 1,'success' => 'Only can use GET method'));
        // return json_encode(array('code'=> 1,'error' => 'Only can use GET method'));
    }
?>
