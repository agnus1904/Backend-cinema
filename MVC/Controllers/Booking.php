<?php

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
