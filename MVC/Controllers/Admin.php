<?php

    class Admin extends Controller 
    {
        private $DB;
        function __construct()
        {
            $this->DB= new DB;
        }
        function CreateNewRoom($cinema_id, $room_type_id, $seat_number)
        {   
            $room = $this->model("AdminModel");
            $room->createNewRoom($cinema_id, $room_type_id, $seat_number);
        }
        function TestBooking(){
            $arr=array();
            $qr = "SELECT * FROM booking_test";
            $result= mysqli_query($this->DB->conn,$qr);
            $row =mysqli_fetch_assoc($result);
            $arr[1]=$row["booking_time"];
            echo json_encode($arr);
        }
        function CreateNewShowTime()
        {
            echo "showtime";
        }
    }
?>
