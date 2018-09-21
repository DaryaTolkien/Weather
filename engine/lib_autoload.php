<?php


$lib_files = scandir(LIB_DIR);

foreach ($lib_files as $file){
    if($file != "." && $file != ".."){
        if(substr($file, -8) == ".lib.php"){
            include_once (LIB_DIR . "/" . $file);
        }
    }
}