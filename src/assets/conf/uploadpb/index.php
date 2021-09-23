<?php
if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="My Realm"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Text, der gesendet wird, falls der Benutzer auf Abbrechen drückt';
    exit;
} else {
    //DB Connection
    include("../config.php");

    if ($conn->error) {
        echo json_encode($conn->error);
        http_response_code(500);
    }

    $user = $conn->real_escape_string($_SERVER['PHP_AUTH_USER']);
    $checkUserSQL = "SELECT * FROM users WHERE email='" . $user . "';";
    $result = $conn->query($checkUserSQL);
    $login = false;

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if ($row["passwordhash"] == hash("sha256", $_SERVER['PHP_AUTH_USER'] . $_SERVER['PHP_AUTH_PW'])) {
                $current_user = $row;
                $login = true;
            }
        }
        if (!$login) {
            http_response_code(401);
            file_put_contents("accessLog.txt", "Access Denied for : " . $_SERVER['PHP_AUTH_USER'] . " | " . date("Y-m-d h:i:sa") . "\n", FILE_APPEND);
        } else {
            if (isset($_FILES["uploadpb"]) && isset($_POST["uploadpb-data"])) {
                //generate Filename
                $target_dir = "../../assets/profilepictures/";
                $filecount = count(scandir($target_dir)) - 1;
                file_put_contents("../loging.txt", $filecount);
                $newfilename = "profilepicture_" . ($filecount) . "." . explode(".", $_FILES["uploadpb"]["name"])[count(explode(".", $_FILES["uploadpb"]["name"])) - 1];
                $target_file = $target_dir . $newfilename;
                $uploadOk = 1;
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                $counter = 0;
                while (file_exists($target_file)) {
                    $counter++;
                    $newfilename = "profilepicture_" . ($filecount + $counter) . "." . explode(".", $_FILES["uploadpb"]["name"])[count(explode(".", $_FILES["uploadpb"]["name"])) - 1];
                    $target_file = $target_dir . $newfilename;
                }

                // Check if image file is a actual image or fake image
                $check = getimagesize($_FILES["uploadpb"]["tmp_name"]);
                if ($check !== false) {
                    $uploadOk = 1;
                } else {
                    $uploadOk = 0;
                }

                // Allow certain file formats
                if (
                    $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                    && $imageFileType != "gif"
                ) {
                    $uploadOk = 0;
                }

                // Check if $uploadOk is set to 0 by an error
                if ($uploadOk == 0) {
                    http_response_code(403);
                } else {
                    if (move_uploaded_file($_FILES["uploadpb"]["tmp_name"], $target_file)) {
                        do {
                            if (file_exists($target_file)) {
                                break;
                            }
                        } while (true);

                        //Delete old File
                        $oldimg = $conn->query("SELECT profilepicture FROM users WHERE id=" . $current_user["id"] . ";")->fetch_assoc()["profilepicture"];
                        if ($oldimg != "defaultpb.jpg" && file_exists($target_dir . $oldimg)) {
                            unlink($target_dir . $oldimg);
                        }

                        //Set New File
                        $conn->query("UPDATE users SET profilepicture='" . $newfilename . "' WHERE id=" . $current_user["id"] . ";");

                        //Crop Image
                        $image_data = json_decode($_POST["uploadpb-data"]);
                        $source = imagecreatefromjpeg($target_file);
                        $im2 = imagecrop($source, ['x' => $image_data->x, 'y' => $image_data->y, 'width' => $image_data->width, 'height' => $image_data->height]);

                        imagejpeg($im2, $target_file);
                    } else {
                        http_response_code(500);
                    }
                }
            } else {
                http_response_code(400);
            }
        }
    } else {
        http_response_code(401);
    }
}

$conn->close();
exit;
