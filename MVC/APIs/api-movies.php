<?php

    function rest(){
        ;
    }
    $arr = array();
    $index=0;
    $newItem=[];
    while($row = mysqli_fetch_assoc($data["movies"])){
        isset($row["movie_id"]) ? $newItem[$index]["movie_id"] = $row["movie_id"] : rest();
        isset($row["movie_name"]) ? $newItem[$index]["movie_name"] = $row["movie_name"] : rest();
        isset($row["movie_name_banner"]) ? $newItem[$index]["movie_name_banner"] = $row["movie_name_banner"] : rest();
        isset($row["banner_url"]) ? $newItem[$index]["banner_url"]  = $row["banner_url"] : rest();
        isset($row["avatar_url"]) ? $newItem[$index]["avatar_url"]  = $row["avatar_url"] : rest();
        isset($row["duration"]) ? $newItem[$index]["duration"]  = $row["duration"] : rest();
        isset($row["main_type"]) ? $newItem[$index]["main_type"]  = $row["main_type"] : rest();
        isset($row["country"]) ? $newItem[$index]["country"] = $row["country"] : rest();
        isset($row["language"]) ? $newItem[$index]["language"] = $row["language"] : rest();
        isset($row["release_date"]) ? $newItem[$index]["release_date"] = $row["release_date"] : rest();
        isset($row["description"]) ? $newItem[$index]["description"] = $row["description"] : rest();
        $index++;
    }
    $arr["data"]=$newItem;

    echo json_encode($arr);