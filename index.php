<?php

include "func.php"; 

create_database('files');

if (!empty($uri) and $datb->is('files', $uri)){
    $fileID = get_from_database('files', $uri);
    redirect($fileID);
}

$headTitle = 'FTLite'; 
include 'partials/head.partials.php';
include 'views/index.view.php';