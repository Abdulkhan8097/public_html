<?php

defined('BASEPATH') OR exit('No direct script access allowed');
if (!function_exists('_pa')) {

    function _pa($array) {
        echo '<pre>';
        print_r($array);
        echo '</pre>';
    }

}

?>