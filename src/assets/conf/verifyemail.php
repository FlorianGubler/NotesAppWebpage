<?php
    if(isset($_GET["mail"])){
        include("config.php");
        $query = "UPDATE users SET email_confirmed = 1 WHERE email='" . $_GET["mail"] . "';";

        $success = $conn->query($query);
    } else{
        http_response_code(404);
        exit;
    }
    if(!$success){
        http_response_code(500);
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .container{
            width: 40%;
            height: 40%;
            background-color: #292827;
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        h1{
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            color: white;
            word-wrap: break-word;
            text-align: center;
            font-size: 2vw;;
        }
    </style>
    <title>E-Mail verification</title>
</head>
<body>
    <div class="container">
        <h1>Thank you, your E-Mail is succesfully verified</h1>
    </div>
</body>
</html>