<?php

function deleteD($dir)
{
    if ($dh = opendir($dir)) {
        while ($file = readdir($dh) ) {
            if (($file == ".") || ($file == "..")) {continue;}
            if (is_dir($dir . '/' . $file)) {
                deleteD($dir . '/' . $file);
            } else {
                unlink($dir . '/' . $file);
            }
        }
        closedir($dh);
        rmdir($dir);
        return TRUE;
    }
    return FALSE;
}

function toArray($str, $saparator) {
    $Array = explode($saparator, $str);
    $csvArray = array_map('trim', $Array);
    return $Array;
  }
  

function str_start_with($str, $match) {
    $n = count(str_split($str));
    while ($n != 0) {
        $n -= 1;
        if (substr( $str, 0, $n ) === $match){
            return TRUE;
        }
    }
    return FALSE;
}

function in($match, $var_) {
#    $var = explode(" ", $var_);
#    if (count($var) != 1) {
#        foreach ($var as $var) {
#            if (in_array($match, $var)) {
#                return TRUE;
#            }
#        }
#        return FALSE;
#    }
    if (str_start_with($var_, $match)) {
        return TRUE;
    }
    return FALSE;
}

function mkdirs($dir) {
    $dir = explode("/", $dir);
    $current = "";
    foreach ($dir as $dir) {
        $current = $current . $dir . "/";
        $current = str_replace("//", "/", $current);
        if (!is_dir($current)){
            mkdir($current);
        }
    }
}


function lastdir($database) {
    $new = explode("/", $database);
    $n = count($new)-1;
    unset($new[$n]);
    $new = implode("/", $new) . "/";
    return $new;
}


class datb {
    
    protected $PATH;
    
    public function __construct($PATH) {
        $this->PATH = $PATH;
    }

    public function create($database) {
        if (!is_dir($this->PATH . $database)) {
            mkdirs($this->PATH . $database);
            file_put_contents($this->PATH . $database . "/value.rf", "");
            return TRUE;
        }
        else {
            return FALSE;
        }
    }

    public function put($database, $var, $value) {
        if (is_dir($this->PATH . $database)) {
            $filename = str_replace("//", "/", $this->PATH . $database . "/value.rf");
            $old = file_get_contents( $filename );
            file_put_contents( $filename , "\n" . $var . "=>" . $value . $old);
            return TRUE;
        }
        else {
            return FALSE;
        }
    }

    public function get($database, $var) {
        if (is_dir( $this->PATH . $database)) {
            $dat = file_get_contents( $this->PATH . $database . "/value.rf");
            $dat = explode("\n", $dat); 

            foreach ($dat as $value) {
                if ($value != ""){
                    $value = explode("=>", $value);
                    if ($value[0] == $var) {
                        return $value[1];
                    }
                }
            }
            return FALSE;
        }
        else {
            return FALSE;
        }
    }

    public function getIn($database, $var) {
        if (is_dir( $this->PATH . $database)) {
            $dat = file_get_contents( $this->PATH . $database . "/value.rf");
            $dat = explode("\n", $dat);
            $output = [];
            $num = 0;

            foreach ($dat as $value) {
                if ($value != ""){
                    $value = explode("=>", $value);
                    if (in($var, $value[0])) {
                        $output[$num] = $value[1]; #add array looop
                        $num += 1;
                    }
                }
            }
            if (!empty($output)) {
                return $output;
            }
            return FALSE;
        }
        else {
            return FALSE;
        }
    }
    

    public function getALL($database, $valu="ALL") {
        if (is_dir( $this->PATH . $database)) {
            $dat = file_get_contents( $this->PATH . $database . "/value.rf");
            $dat = explode("\n", $dat);
            $dat_full = [];
            foreach ($dat as $value) {
              if ($valu == "ALL" && $value != "") {
                        $value = explode("=>", $value);
                        $dat_full[$value[0]] = $value[1];
                       }
                    elseif ($value != "") {
                            $value = explode("=>", $value);
                        if ($value[0] == $valu){
                            $dat_full[$value[0]] = $value[1];
                        }
                }
            }
            # print_r($dat_full);
            return $dat_full;
        }
        return FALSE;
    }

    public function is($database, $var) {
        if (is_dir( $this->PATH . $database)) {
            $dat = file_get_contents( $this->PATH . $database . "/value.rf");
            $dat = explode("\n", $dat); 

            foreach ($dat as $value) {
                $value = explode("=>", $value);
                if ($value[0] == $var) {
                    return TRUE;
                }
            }
            return FALSE;
        }
        else {
            return FALSE;
        }
    }


    public function removeV($database, $var) {
        if (is_dir( $this->PATH . $database)) {
            $dat = file_get_contents($this->PATH . $database . "/value.rf");
            if ($this->is($database, $var)){
                $fol = $var . "=>" . $this->get($database, $var);
                $dat = str_replace("\n\n", "\n", str_replace($fol, "", $dat));
                file_put_contents($this->PATH . $database . "/value.rf", $dat);
            }
            return FALSE;
        }
        else {
            return FALSE;
        }
    }

    public function isdat($database) {
        if (is_dir( $this->PATH . $database)) {
            return TRUE;
        }
        return FALSE;
    }
    public function remove($database) {
        if (is_dir( $this->PATH . $database)) {
            if (deleteD( $this->PATH . $database)){
                return TRUE;
            }
        }
        else {
            return FALSE;
        }
    }

    public function add($database, $var, $new_value) { # adds from the old value
        if (is_dir( $this->PATH . $database)) {
            $filename = $this->PATH . $database . "/value.rf";
            $dat = file_get_contents( $filename );
            if ($this->is($database, $var)){
                $fol = $var . "=>" . $this->get($database, $var);
                $dat = str_replace($fol, $fol . $new_value, $dat);
                file_put_contents($filename , $dat);
                return TRUE;
            }
            return FALSE;
        }
        else {
            return FALSE;
        }
    }
    
    
    public function update($database, $var, $new_value) { # changes the value
        if (is_dir( $this->PATH . $database)) {
            $dat = file_get_contents( $this->PATH . $database . "/value.rf");
            if ($this->is($database, $var)){
                $fol = $var . "=>" . $this->get($database, $var);
                $new = $var . "=>" . $new_value;
                $dat = str_replace($fol, $new, $dat);
                file_put_contents($this->PATH . $database . "/value.rf" , $dat);
                return TRUE;
            }
            return FALSE;
        }
        else {
            return FALSE;
        }
    }
}

