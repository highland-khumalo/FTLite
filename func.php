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

// this function is used on pages that need the real user
function security_check()
{
    require "__/get_ip.php";
    global $datb;
    if (
        isset($_COOKIE['remember_token']) and isset($_COOKIE['phone_number']) and
        $datb->get("users/" . $_COOKIE['phone_number'], "token") == $_COOKIE['remember_token'] and
        $ip == $datb->get("users/" . $_COOKIE['phone_number'], "ip")
    ) {
        return TRUE;
    } else {
        if (isset($_COOKIE['remember_token'])) {
            log_("security_check: false reading on \n\t\tphone no =" . isset($_COOKIE['phone_number']) . "\n\t\tip =" .
                $ip . " -> " . $datb->get("users/" . $_COOKIE['phone_number'], "ip") . "\n\t\ttokens" .
                $_COOKIE['remember_token'] . " -> " . $datb->get("users/" . $_COOKIE['phone_number'], "token"));    
        }

        if (isset($_SERVER['HTTP_COOKIE'])) {
            $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
            foreach ($cookies as $cookie) {
                $parts = explode('=', $cookie);
                $name = trim($parts[0]);
                setcookie($name, '', time() - 1000);
                setcookie($name, '', time() - 1000, '/');
            }
        }
        redirect("/");
    }
}

function sanitizeString($string)
{
    return htmlspecialchars($string, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

function startsWith($str, $search)
{
    return substr($str, 0, strlen($search)) == $search;
}

function formatPhoneNumber($phoneNumber)
{
    if (startsWith($phoneNumber, "+27")) {
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
        $length = strlen($phoneNumber);
        if ($length == 10) {
            return '+27 ' . substr($phoneNumber, 0, 2) . ' ' . substr($phoneNumber, 2, 3) . ' ' . substr($phoneNumber, 5);
        } else {
            return $phoneNumber;
        }
    }
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

function add_new_contact($my_phone_number, $input_phone_number)
{
    // Save the phone number to a database
    global $datb;
    if (!$datb->is("users/" . $my_phone_number . "/contact", $input_phone_number)) {
        $datb->add("users/" . $my_phone_number, "contacts", $input_phone_number . ",");
        $datb->put("users/" . $my_phone_number . "/contact", $input_phone_number, $input_phone_number . ",");
    }
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
