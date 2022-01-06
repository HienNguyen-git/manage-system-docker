<?php
    ob_start();
    require_once('db.php');

    if($_SERVER['REQUEST_METHOD']!='POST'){
        http_response_code(405);
        die(json_encode(array('code'=>1,'message'=>'API nay chi ho tro POST')));
    }

    $input = json_decode(file_get_contents('php://input'));

    // die(json_encode(array('code'=>5,'data'=> array($input->id,$input->name,$input->price,$input->desc))));

    if(is_null($input)){
        die(json_encode(array('code'=>2,'message'=>'Chi ho tro JSON')));
    }

    if(!property_exists($input,'firstname')||!property_exists($input,'lastname')){
        http_response_code(400);
        die(json_encode(array('code'=>3,'message'=>'Thieu thong tin dau vao ')));
    }

    if(empty($input->firstname)||empty($input->lastname)){
        http_response_code(405);
        die(json_encode(array('code'=>4,'message'=>'Thong tin khong hop le')));
    }

    $id = $input->id;
    $firstname = $input->firstname;
    $lastname = $input->lastname;
    // die(json_encode(array('code'=>5,'data'=> array($id,$name,$price,$desc))));

    
    $sql = 'update employee set firstname=?, lastname=? where id=?';
    $conn = open_database();
    
    $stm = $conn->prepare($sql);
    $stm->bind_param('ssi',$firstname,$lastname,$id);
    
    if(!$stm->execute()){
        http_response_code(400);
        die(json_encode(array('code'=>5,'message'=>'Khong the thuc hien lenh')));
    }
    die(json_encode(array('code'=>0,'message'=>'Da them san pham thanh cong')));
?>
<?php
    ob_end_flush();
?>