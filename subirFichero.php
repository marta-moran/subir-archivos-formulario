<html>
<head>
<title>Procesa una subida de archivos </title>
<meta charset="UTF-8">
</head>
<?php

$error=false;
$numArchivos=count($_FILES['imagen']['name']); 
$directorioSubida= '/home/marta/imgusers/';

define('TAMAÑOMAXTOTAL', 300000);
define('TAMAÑOMAXFICHERO', 200000);

define('ERROR_NO_JPG_PNG',     5000);
define('ERROR_MAX_TAMAÑOIMG',  5001);
define('ERROR_MAX_TAMAÑOTOTAL', 5002);

$codigosErrorSubida= [ 

    UPLOAD_ERR_OK         => 'Subida correcta',  // Valor 0
    UPLOAD_ERR_INI_SIZE   => 'El tamaño del archivo excede el admitido por el servidor',  // directiva upload_max_filesize en php.ini valoe 1
    UPLOAD_ERR_FORM_SIZE  => 'El tamaño del archivo excede el admitido por el cliente',  // directiva MAX_FILE_SIZE en el formulario HTMl valor 2
    UPLOAD_ERR_PARTIAL    => 'El archivo no se pudo subir completamente', //valor 3
    UPLOAD_ERR_NO_FILE    => 'No se seleccionó ningún archivo para ser subido', // valor 4
    UPLOAD_ERR_NO_TMP_DIR => 'No existe un directorio temporal donde subir el archivo',
    UPLOAD_ERR_CANT_WRITE => 'No se pudo guardar el archivo en disco',  // permisos
    UPLOAD_ERR_EXTENSION  => 'Una extensión PHP evito la subida del archivo',  // extensión PHP
    
    //codigos propios del programa
    ERROR_NO_JPG_PNG         => 'Formato de fichero no admitido',
    ERROR_MAX_TAMAÑOIMG      => 'El archivo supera el tamaño máximo permitido',
    ERROR_MAX_TAMAÑOTOTAL    => 'El total de los archivos supera el máximo permitido '

]; 

if (count($_POST) == 0 ){
   echo "Error: se supera el tamaño máximo de un petición POST' ";
 } 
    for($i = 0; $i < $numArchivos; $i++) {
        $nombre_imagen   =   $_FILES['imagen']['name'][$i];
        $tipo_imagen    =   $_FILES['imagen']['type'][$i];
        $tamanio_imagen =   $_FILES['imagen']['size'][$i];
        $temporal_imagen =   $_FILES['imagen']['tmp_name'][$i];
        $error_imagen   =   $_FILES['imagen']['error'][$i];

        if(!comprobarExtension($nombre_imagen)) {
            echo $codigosErrorSubida[ERROR_NO_JPG_PNG];
            $error=true;
        } else if (array_sum($_FILES['imagen']['size'])>TAMAÑOMAXTOTAL) {
            echo $codigosErrorSubida[ERROR_MAX_TAMAÑOTOTAL];
            $error=true;
            
        } else if ($tamanio_imagen>TAMAÑOMAXFICHERO){
            echo $codigosErrorSubida[ERROR_MAX_TAMAÑOIMG];
            $error=true;
        } else if ((file_exists($directorioSubida.$nombre_imagen))){
            echo "Ya existe";
            $error=true;
        }

        if(!$error) {
            if (move_uploaded_file($temporal_imagen, $directorioSubida.$nombre_imagen)){
                echo "Se ha subido {$nombre_imagen}<br>";
            } 
        } else {
            break;
        }
    }

function comprobarExtension($nombre_imagen) {
    $extension= pathinfo($nombre_imagen, PATHINFO_EXTENSION);
    $extensionesValidas = array("jpg", "png");
    $resultado=false;
    if(in_array($extension, $extensionesValidas)) {
        $resultado=true;
    }
    return $resultado; 
}
?>
<body>
<br><a href="index.html">Volver a la página de subida</a>
</body>
</html>