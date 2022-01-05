<?php 
    require_once('../admin/db.php');

    // if(is_task_submitted(2)){
    //     echo "Submitted";
    // }else{
    //     echo "Not yet";
    // }
    // update_approval_date('hiengay');
    // print_r(get_taskdetail_byid(1));
    // print_r(get_employee_bydepartment('IT'));
    $deadline = "01-03-2022";
    $extend_date = date("Y-m-d", strtotime($deadline))." 00:00:00";
    echo $extend_date;
    update_deadline(5,$extend_date)
    // print_r(get_department_byuser('hiengay')['data']['department']);
    // print_r(get_tasks('Accountant'));    

?>