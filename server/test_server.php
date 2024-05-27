<?php
    $absolutePath = realpath(__DIR__);

    $pathArray =  explode('\\', $absolutePath);
    $currentFile = $pathArray[count($pathArray) - 1];
    echo $currentFile;
    
    
?>