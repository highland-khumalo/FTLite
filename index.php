<?php

include "func.php"; 

create_database('files');

if ($datb->is('files', $uri)){
    $fileID = get_from_database('files', $uri);
    redirect($fileID);
}