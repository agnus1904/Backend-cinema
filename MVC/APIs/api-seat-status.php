<?php

    function rest(){
        ;
    }
    $arr = array();
    $index=0;
    $newItem=[];
    while($row = mysqli_fetch_assoc($data["seats"])){
        isset($row["seat_status_on_show_time_id"]) ?
            $newItem[$index]["seat_status_on_show_time_id"] = $row["seat_status_on_show_time_id"] :
            rest();
        isset($row["seat_status"]) ?
            $newItem[$index]["seat_status"] = $row["seat_status"] :
            rest();
        $index++;
    }
    $arr["data"]=$newItem;

    echo json_encode($arr);