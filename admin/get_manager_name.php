<?php 
    require_once('db.php');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST');
    header("Access-Control-Allow-Headers: X-Requested-With");

    if(isset($_GET['department'])){
        $department = $_GET['department'];
        $sql = "select username from employee where role = 'employee' and department = ? ";
        $conn = open_database();
    
        $stm = $conn->prepare($sql);
        $stm->bind_param('s',$department);
    
        if(!$stm->execute()){
            return array('code'=>1,'error'=>'Command not execute');
        }
    
        $result = $stm->get_result();
        $data = array();
        if($result->num_rows==0){
            print_r(json_encode(array('code'=>2,'error'=>'This department not have employee')));
            die();
        }else{
            while($row = $result->fetch_assoc()){
                $data[] = $row['username'];
            }
        }
        print_r(json_encode(array('code'=>0,'data'=>$data))) ;
    }
?>