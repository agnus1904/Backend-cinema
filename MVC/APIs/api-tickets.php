<?php


    function rest(){
        ;
    }
    $arr = array();
    $index=0;
    $newItem=[];
    while($row = mysqli_fetch_assoc($data["tickets"])){
        $newItem[$index]["avatar_url"] = $row["avatar_url"];
        isset($row["ticket_id"]) ? $newItem[$index]["ticket_id"] = $row["ticket_id"] : rest();
        $newItem[$index]["movie_name"] = $row["movie_name"];
        $newItem[$index]["province_name"] = $row["province_name"];
        $newItem[$index]["cinema_name"] = $row["cinema_name"];
        $newItem[$index]["room_name"] = $row["room_name"];
        $newItem[$index]["customer_name"] = $row["customer_name"];
        $newItem[$index]["show_time_date"] = $row["show_time_date"];
        $newItem[$index]["show_time_end"] = $row["show_time_end"];
        $newItem[$index]["ticket_date"] = $row["ticket_date"];
        $newItem[$index]["ticket_price"] = $row["ticket_price"];
        $newItem[$index]["seat_name"] = $row["seat_name"];
        $index++;
    }
    $arr["data"]=$newItem;

    echo json_encode($arr);