<?php 
require_once 'config/config.php';
require_once 'classes/BaseFunctions.php';

$baseFunctions = new BaseFunctions();

if ( !empty($baseFunctions->view) ) {
    $view = $baseFunctions->view;
}

if ( !empty($baseFunctions->redirect) ) {
    header("location: ".$baseFunctions->redirect);
    exit();
}

if (!$baseFunctions->user_is_logged_in&&$view!="b_acc_register") {
    $view = "b_acc_login";
}

$view_file = 'views/'.$view.'.php';

if (file_exists($view_file)) {
    include_once "$view_file";
}else {
    include_once 'views/f_index.php';
}