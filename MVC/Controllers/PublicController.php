<?php

header('Content-Type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Methods: PUT, GET, POST");
    header("Access-Control-Allow-Headers: X-Requested-With");
    class PublicController extends Controller
    {
        function GetProvince()
        {
            $arr = array();
            $index=0;
            $newItem=[];
            $result=$this->model("PublicModel")->getProvince();
            while($row = mysqli_fetch_assoc($result)){
                $newItem[$index]["province_id"] = $row["province_id"];
                $newItem[$index]["province_name"] = $row["province_name"];
                $index++;
           }
           $arr["data"]=$newItem;
           echo json_encode($arr);
        }

        function GetCinema($province_id)
        {
            $arr = array();
            $index=0;
            $newItem=[];
            $result=$this->model("PublicModel")->getCinema($province_id);
            while($row = mysqli_fetch_assoc($result)){
                $newItem[$index]["cinema_id"] = $row["cinema_id"];
                $newItem[$index]["cinema_name"] = $row["cinema_name"];
                $index++;
           }
           $arr["data"]=$newItem;
           echo json_encode($arr);
        }

        function GetRoom($cinema_id)
        {
            $arr = array();
            $index=0;
            $newItem=[];
            $result=$this->model("PublicModel")->getRoom($cinema_id);
            while($row = mysqli_fetch_assoc($result)){
                $newItem[$index]["room_id"] = $row["room_id"];
                $newItem[$index]["room_name"] = $row["room_name"];
                $newItem[$index]["seat_number"] = $row["seat_number"];
                $index++;
            }
           $arr["data"]=$newItem;
           echo json_encode($arr);
        }

        function GetAllMovie()
        {
            $movies = $this->model("PublicModel");
            $this->api("api-movies", [
                "movies" => $movies->getAllMovie(),
            ]);
        }

        function GetAllMovieByName($input)
        {
            $movies = $this->model("PublicModel");
            $this->api("api-movies", [
                "movies" => $movies->getAllMovieByName($input),
            ]);
        }
        function GetAllActor()
        {
            $actors = $this->model("PublicModel");
            $this->api("api-actors",[
                "actors" => $actors->getAllActor(),
            ]);
        }

        function Mailer()
        {
            
        }

        function GetShowTime($cinema, $movie, $time)
        {
            $arr = array();
            $index=0;
            $newItem=[];
            $result=$this->model("PublicModel")->getShowTime($cinema, $movie, $time);
            while($row = mysqli_fetch_assoc($result)){
                $newItem[$index]["show_time_id"] = $row["show_time_id"];
                $newItem[$index]["room_id"] = $row["room_id"];
                $newItem[$index]["room_name"] = $row["room_name"];
                $newItem[$index]["show_time_date"] = $row["show_time_date"];
                $index++;
           }
           $arr["data"]=$newItem;  
           echo json_encode($arr);
        }

        function GetSeatStatus($show_time_id)
        {

            $seatsStatus= $this->model("PublicModel")->getSeatStatus($show_time_id);
            $this->api("api-seat-status",[
                "seats" => $seatsStatus
            ]);
        }

        function GetShowTimeByRoom($room, $movie, $time)
        {
            $arr = array();
            $index=0;
            $newItem=[];
            $result=$this->model("PublicModel")->getShowTimeByRoom($room, $movie, $time);
            while($row = mysqli_fetch_assoc($result)){
                $newItem[$index]["show_time_id"] = $row["show_time_id"];
                $newItem[$index]["room_name"] = $row["room_name"];
                $newItem[$index]["room_id"] = $row["room_id"];
                $newItem[$index]["show_time_date"] = substr($row["show_time_date"],11);
                $newItem[$index]["show_time_end"] = substr($row["show_time_end"],11);
                $newItem[$index]["movie_name"] = $row["movie_name"];
                $index++;
           }
           $count = count($newItem);
           if($count===0){
                $newItem[0]["show_time_id"] = "";
                $newItem[0]["room_name"] = "";
                $newItem[0]["room_id"] = $room;
                $newItem[0]["show_time_date"] = "";
                $newItem[0]["show_time_end"] = "";
                $newItem[$index]["movie_name"] = "";
           }
           $arr["data"]=$newItem;
           echo json_encode($arr);
        }
    }
?>
