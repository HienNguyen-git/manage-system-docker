<?php
    require_once('db.php');

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
    $currentName = $input->currentName;
    $sql = 'delete from employee where id=?';
    $conn = open_database();
    
    $stm = $conn->prepare($sql);
    $stm->bind_param('i',$id);
    
    if(!$stm->execute()){
        http_response_code(400);
        die(json_encode(array('code'=>5,'message'=>'Khong the thuc hien lenh')));
    }
    delete_absence_info($currentName);
    die(json_encode(array('code'=>0,'message'=>'Da xoa thanh cong')));
?>