<?php
// app/Helpers/lang_helper.php

if (!function_exists('lang')) {
    function lang($key){
        $lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'en';
        static $translations = [];
        
        if(!isset($translations[$lang])){
            $path = APPROOT . '/Languages/' . $lang . '.php';
            if(file_exists($path)){
                $translations[$lang] = require $path;
            } else {
                $translations[$lang] = [];
            }
        }
        return isset($translations[$lang][$key]) ? $translations[$lang][$key] : $key;
    }
}