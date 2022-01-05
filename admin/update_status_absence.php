<?php 
    require_once('db.php');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST');
    header("Access-Control-Allow-Headers: X-Requested-With");
    

    if(isset($_GET['id']) && isset($_GET['username'])){
        $id = $_GET['id'];
        $username = $_GET['username'];

        $sql = "update absence_form set status = 'Approved' where id = ?";
        $conn = open_database();
    
        $stm= $conn->prepare($sql);
        $stm->bind_param('i',$id);
    
        if(!$stm->execute()){
            return array('code' => 2, 'error' => 'Cant execute command');
        }
        update_dayused($id,$username);
        header("Location: dayoffDetail.php?id=$id");
        // return array('code' => 0, 'success' => 'Password reset');
    }


?>