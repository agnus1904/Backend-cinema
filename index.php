<?php
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');

    header('Access-Control-Allow-Methods: GET, POST, PUT');

    header("Access-Control-Allow-Headers: X-Requested-With");

    require_once "./MVC/Bridge.php";
    $myApp = new App();
?>