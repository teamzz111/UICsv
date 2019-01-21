<?php

    if ( isset($_FILES["file"])) 
    {
        if ($_FILES["file"]["error"] > 0) 
        {
            echo "Ocurrió una tragedia: " . $_FILES["file"]["error"] . "<br />";
        }
        else 
        {
            $multiQuery = "";
            $pattern = '/^(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){255,})(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){65,}@)(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22))(?:\\.(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-+[a-z0-9]+)*\\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-+[a-z0-9]+)*)|(?:\\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\\]))$/iD';
            $file = fopen($_FILES['file']['tmp_name'], 'r');
            
            if($file)
            {
                $index = 0;
                
                while (($line = fgetcsv($file, 1000, ';')) !== FALSE) 
                {

                    if(sizeof($line) <  7)
                    {
                        echo "Campos incompletos en la línea " . $index;
                        die();       
                    }

                    if(sizeof($line) > 7)
                    {
                        echo "Hay más parámetros de los esperados en la línea " . $index;
                        die();       
                    }

                    if(!is_numeric($line[0]))
                    {
                        echo "Campo cédula debe ser numérico en la línea " . $index;
                        die();
                    }
                    if(!(preg_match($pattern, $line[1]) === 1))
                    {
                        echo "Campo correo debe ser válido en la línea " . $index;
                        die();
                    }
                    
                    if(strlen($line[2]) < 1)
                    {
                        echo "Campo nit deben tener contenido o no sobrepasar los 64 carácteres " . $index;
                        die();
                    }

                    $nombre = strlen($line[3]);
                    $apellido = strlen($line[4]);

                    if($nombre == 0 || $nombre > 64 || $apellido == 0 || $apellido > 64)
                    {
                        echo "Campo nombres y apellidos deben tener contenido o no sobrepasar los 64 carácteres " . $index;
                        die();
                    }

                    if(!is_numeric($line[5]))
                    {
                        echo "Campo tipo_usuario no es válido en la línea " . $index;
                        die();
                    }

                    if(!is_numeric($line[6])){
                        echo "Campo teléfono no es válido en la línea " . $index;
                        die();
                    }

                    $multiQuery = $multiQuery. "INSERT INTO cliente VALUES('$line[0]','$line[1]',
                     '$line[2]', '$nombre', '$apellido', $line[5], '$line[6]');"; 

                    $index++;
                }
                fclose($file);
                echo $multiQuery;
            }
        }
    
    } else {
        echo "Sube el archivo de nuevo";
    }
    
?>