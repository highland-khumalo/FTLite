<?php

include "func.php"; 

create_database('files');

if (!empty($uri) and $datb->is('files', $uri)){
    $fileID = get_from_database('files', $uri);
    redirect($fileID);
}

if (isset($_GET['url']) and $datb->is('files', $_GET['url'])){
    $fileID = get_from_database('files', $_GET['url']);
    redirect($fileID);
}

$headTitle = 'FTLite'; 
include 'partials/head.partials.php';
include 'views/index.view.php';