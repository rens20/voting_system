<?php





function ValidateLogin($username, $password){
     
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME); 
    $sql = "SELECT * FROM users WHERE username = '$username' && password = '$password'";
    $result = $conn->query($sql);
    $row = mysqli_fetch_assoc($result);

    return $row;
    
}


function Register($username, $email, $password){
    

    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME); 


        $insert = "INSERT INTO users (username, email, password, type) VALUES ('$username', '$email', '$password', 'user')";

        if ($conn->query($insert) === TRUE) {
            
            $report = 'Registered Complete!';
            header("location: ../public/user.php");

        }else{

            $report = 'Error Database!';
        }


    

    return $report;




}
