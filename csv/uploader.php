<?php
    if ( isset($_POST["submit"]) ) {

        if ( isset($_FILES["file"])) {
    
            if ($_FILES["file"]["error"] > 0) {
                echo "Ocurrió una tragedia: " . $_FILES["file"]["error"] . "<br />";
            }
            else {
                echo "Upload: " . $_FILES["file"]["name"] . "<br />";
                echo "Type: " . $_FILES["file"]["type"] . "<br />";
                echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
                echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";
    
                   /*
                if (file_exists("upload/" . $_FILES["file"]["name"])) {
                echo $_FILES["file"]["name"] . " already exists. ";
                }
                else {
                        //Store file in directory "upload" with the name of "uploaded_file.txt"
                $storagename = "uploaded_file.txt";
                move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $storagename);
                echo "Stored in: " . "upload/" . $_FILES["file"]["name"] . "<br />";
                }*/
            }
        
        }
    }

?>