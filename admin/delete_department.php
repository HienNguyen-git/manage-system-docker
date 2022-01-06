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
    if($_SERVER['REQUEST_METHOD']!='DELETE'){
        http_response_code(405);
        die(json_encode(array('code'=>1,'message'=>'API nay chi ho tro POST')));
    }

    $input = json_decode(file_get_contents('php://input'));
    // die(json_encode(array('code'=>2,'data'=>$input->id)));

    if(is_null($input)){
        die(json_encode(array('code'=>2,'message'=>'Chi ho tro JSON')));
    }

    if(!property_exists($input,'id')){
        http_response_code(400);
        die(json_encode(array('code'=>3,'message'=>'Thieu thong tin dau vao ID')));
    }

    if(empty($input->id)){
        http_response_code(405);
        die(json_encode(array('code'=>4,'message'=>'Thong tin khong hop le')));
    }

    $id = $input->id;
    $sql = 'delete from department where id=?';
    $conn = open_database();
    
    $stm = $conn->prepare($sql);
    $stm->bind_param('i',$id);
    
    if(!$stm->execute()){
        http_response_code(400);
        die(json_encode(array('code'=>5,'message'=>'Khong the thuc hien lenh')));
    }
    die(json_encode(array('code'=>0,'message'=>'Da xoa thanh cong')));
?>

<?php
    ob_end_flush();
?>