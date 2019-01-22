<?php
header('Content-Type: text/html; charset=utf-8');
    // Global configuration for fields expected

    global $fieldsExpected;
    $fieldsExpected = 7;
    // DB Connection

    $user = "root";
    $password = "";
    $server = "localhost";
    $db = "cliente";

    if ( isset($_FILES["file"])) 
    {
        if ($_FILES["file"]["error"] > 0) 
        {
            echo json_encode(["status" => 0, "message" => $_FILES["file"]["error"]]);
        }
        else 
        {
            $multiQuery = "";
            $file = fopen($_FILES['file']['tmp_name'], 'r');
            
            if($file)
            {
                $foundCharacter;
                
                //Check firstline 
                $testingString = fgets($file);
    
                // Check if 'comma' is avaliable in the CSV file
                $characterComma = preg_split('/,/', $testingString, -1, PREG_SPLIT_NO_EMPTY);
                $caractererDot = preg_split('/;/', $testingString, -1, PREG_SPLIT_NO_EMPTY);
    
                //Check if comma exist, with size.
                if(sizeof($characterComma) > 0)
                {
                    $foundCharacter = ',';
                } 
                else if(sizeof($characterDot) > 0)
                {
                    $foundCharacter = ';';
                }

                $firstLine = explode($foundCharacter, $testingString);
                
                // Special check for first line
                
                checkParameters($firstLine, $fieldsExpected, 0);
                
                $multiQuery = $multiQuery. "INSERT INTO cliente VALUES('$firstLine[0]','$firstLine[1]',
                '$firstLine[2]', '$firstLine[3]', '$firstLine[4]', $firstLine[5], '$firstLine[6]');"; 

                
                $index = 1;

                while (($line = fgetcsv($file, 1000, ',')) !== FALSE) 
                {
         
                    checkParameters($line, $fieldsExpected, $index);

                    $multiQuery = $multiQuery. "INSERT INTO cliente VALUES('$line[0]','$line[1]',
                     '$line[2]', '$line[3]', '$line[4]', $line[5], '$line[6]');"; 

                    $index++;
                }
                fclose($file);
                
                $connection = new mysqli($server, $user, $password, $db);
    
                echo json_encode(["status" => 1, "message" => "Verificación exitosa"]);

                $connection->close();
            }
        }

    } 
    else 
    {
        echo "Sube el archivo de nuevo";
    }

function checkParameters($line, $fieldsExpected, $index)
{
    $pattern = '/^(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){255,})(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){65,}@)(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22))(?:\\.(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-+[a-z0-9]+)*\\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-+[a-z0-9]+)*)|(?:\\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\\]))$/iD';

    if(sizeof($line) <  $fieldsExpected)
    {
        echo json_encode(["status" => 0, "message" => "Campos incompletos en $index"]);
        die();       
    }

    if(sizeof($line) >  $fieldsExpected)
    {
        echo json_encode(["status" => 0, "message" => "Hay más campos de los esperados en la línea $index"]);
        die();       
    }

    if(!is_numeric(trim($line[0])))
    {
        echo json_encode(["status" => 0, "message" => "Campos cédula debe ser numérico en $index"]);
        die();
    }
    if(!(preg_match($pattern, trim($line[1])) === 1))
    {
        echo json_encode(["status" => 0, "message" => "Campo correo no válido en la línea $index"]);
        die();
    }
    
    if(strlen(trim($line[2])) < 1)
    {
        echo json_encode(["status" => 0, "message" => "Campos nit deben tener contenido en la línea $index"]);
        die();
    }

    $nombre = strlen(trim($line[3]));
    $apellido = strlen(trim($line[4]));

    if($nombre == 0 || $nombre > 64 || $apellido == 0 || $apellido > 64)
    {
        echo json_encode(["status" => 0, "message" => "Campos nombre y apellidos no deben pasar los 64 carácteres en la línea $index"]);
        die();
    }

    if(!is_numeric(trim($line[5])))
    {
        echo json_encode(["status" => 0, "message" => "Campos tipo_cliente no válido en la línea $index"]);
        die();
    }

    $newtext = str_replace("\n", '', $line[6]);

    if(!is_numeric(trim("$newtext"))){
        echo json_encode(["status" => 0, "message" => "Campos teléfono debe ser numérico en la línea $index"]);
        die();
    }
        
}
?>