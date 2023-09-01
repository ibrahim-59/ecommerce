<?php

include "../connect.php";

$username = filterRequest("username");
$password = sha1("password");
$email = filterRequest("email");
$phone = filterRequest("phone");
$verifycode = "0";

$stmt = $con ->prepare("SELECT * FROM users WHERE users_email = ? OR users_phone = ?");
$stmt->execute(array($email , $phone));
$count =  $stmt -> rowCount();

if($count > 0) {
    printFailure("Phone Or Email Exist");
}else {
    $data = array(
        "users_name" => $username,
        "users_email" => $email,
        "users_phone "=> $phone,
        "users_password" => $password,
        "users_verifycode" => "0",
    );
    // insertData("users" , $data);
    addData($username , $email , $phone, $password);
}


function addData($username , $email , $phone, $password  ){
    global $con;
    $stmt = $con->prepare(
        "INSERT INTO `users`(`users_name`, `users_email`, `users_phone` , `users_password` , `users_verifycode`) 
        VALUES (?,?,?,?,?)
        ");
        
        $stmt -> execute(array($username , $email , $phone,$password ,"0"));
        
        $count = $stmt-> rowCount();
        
        if($count > 0) {
            echo json_encode(array("status" => "success"));
        }else {
            echo json_encode(array("status" => "failed"));
        }
}