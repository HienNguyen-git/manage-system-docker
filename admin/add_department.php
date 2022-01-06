<?php
    ob_start();
    require_once('db.php');
    
    $me = get_info_employee_byuser($_SESSION['user']);
	if($me['role'] != 'admin' ){
        move_page($me['role']);
        exit();
    }

    if($_SERVER['REQUEST_METHOD']!='POST'){
        http_response_code(405);
        die(json_encode(array('code'=>1,'message'=>'API nay chi ho tro POST')));
    }

    $input = json_decode(file_get_contents('php://input'));
 
    if(is_null($input)){
        die(json_encode(array('code'=>2,'message'=>'Chi ho tro JSON')));
    }

    if(!property_exists($input,'departmentNameAdd')||!property_exists($input,'departmentNumAdd') ||!property_exists($input,'departmentDetailAdd')){
        http_response_code(400);
        die(json_encode(array('code'=>3,'message'=>'Thieu thong tin dau vao ID'))); 
    }

    if(empty($input->departmentNameAdd)||empty($input->departmentNumAdd) ||empty($input->departmentDetailAdd)){
        http_response_code(405);
        die(json_encode(array('code'=>4,'message'=>'Input is not valid')));
    }

    $departmentNameAdd = $input->departmentNameAdd;
    $departmentNumAdd = $input->departmentNumAdd;
    // $departmentManagerAdd = $input->departmentManagerAdd;
    $departmentDetailAdd = $input->departmentDetailAdd;

    $sql = 'INSERT INTO department(name, number_room, detail) VALUES (?,?,?)';
    $conn = open_database();
    
    $stm = $conn->prepare($sql);
    $stm->bind_param('sis',$departmentNameAdd,$departmentNumAdd,$departmentDetailAdd);
    
    if(!$stm->execute()){
        http_response_code(400);
        die(json_encode(array('code'=>5,'message'=>'Khong the thuc hien lenh')));
    }
    die(json_encode(array('code'=>0,'message'=>'Da them san pham thanh cong')));
?>

<?php
    ob_end_flush();
?>