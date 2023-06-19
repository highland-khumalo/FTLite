<?php

require "config.php";
require "datb/datb.php";
$datb = new datb($config['datb_storage']);


$uri = $_SERVER['REQUEST_URI'];
$now = new DateTime();
$format = "h:i A j M";

function dd($value)
{

    echo '<pre>';
    var_dump($value);
    echo '</pre>';

    die();
}

function redirect($url)
{
    header("Location: " . $url);
    exit();
}


function sanitizeString($string)
{
    return htmlspecialchars($string, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

function startsWith($str, $search)
{
    return substr($str, 0, strlen($search)) == $search;
}

function generateRandomString($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $string = '';

    for ($i = 0; $i < $length; $i++) {
        $randomIndex = rand(0, strlen($characters) - 1);
        $string .= $characters[$randomIndex];
    }

    return $string;
}


function create_database($DATABASE)
{
    global $datb;
    $datb->create($DATABASE);
}

function put_to_database($DATABASE, $var, $value)
{
    global $datb;
    if ($datb->isdat($DATABASE)) {
        $value = sanitizeString($value);
        $value = str_replace(",", "%!_c.m_!%", $value);
        $datb->put($DATABASE, $var, $value);
    }
}

function get_from_database($DATABASE, $var)
{
    global $datb;
    if ($datb->is($DATABASE, $var)) {
        $value = $datb->get($DATABASE, $var);
        $value = str_replace("%!_c.m_!%", ",", $value);
        return $value;
    }
    return "";
}

function cache($var, $value)
{
    create_database("users/" . $_COOKIE['phone_number'] . "/cache");
    put_to_database("cache", $var, $value);
    return TRUE;
}

function cachedif($var, $Cvalue)
{
    create_database("users/" . $_COOKIE['phone_number'] . "/cache");
    if (get_from_database("cache", $var) === $Cvalue) {
        return TRUE;
    } else {
        return FALSE;
    }
}

function cacheold($var)
{
    create_database("users/" . $_COOKIE['phone_number'] . "/cache");
    if (get_from_database("cache", $var)) {
        return get_from_database("cache", $var);
    }
}


function log_($massege)
{
    $old = file_get_contents("log.txt");
    file_put_contents("log.txt", $old . date('Y-m-d H:i:s') . " => $massege\n");
}

function log_clear()
{
    file_put_contents("log.txt", "");
}
