<?php

header('Content-Type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Methods: PUT, GET, POST");
    header("Access-Control-Allow-Headers: X-Requested-With");
    class Booking extends Controller
    {
        function GetBooking()
        {   
            $id = $this->model("LoginModel");
            $this->api("api-home-banner", [
            ]);
        }
        
    }
?>
