<?php

    function rest(){
        ;
    }
    $arr=array();
    $newItem=[];
    while($row = mysqli_fetch_assoc($data["movie"])){
            isset($row["movie_id"]) ? $newItem["movie_id"] = $row["movie_id"] : rest();
            isset($row["movie_name"]) ? $newItem["movie_name"] = $row["movie_name"] : rest();
            isset($row["movie_name_banner"]) ? $newItem["movie_name_banner"] = $row["movie_name_banner"] : rest();
            isset($row["banner_url"]) ? $newItem["banner_url"]  = $row["banner_url"] : rest();
            isset($row["avatar_url"]) ? $newItem["avatar_url"]  = $row["avatar_url"] : rest();
            isset($row["duration"]) ? $newItem["duration"]  = $row["duration"] : rest();
            isset($row["main_type"]) ? $newItem["main_type"]  = $row["main_type"] : rest();
            isset($row["country"]) ? $newItem["country"] = $row["country"] : rest();
            isset($row["language"]) ? $newItem["language"] = $row["language"] : rest();
            isset($row["release_date"]) ? $newItem["release_date"] = $row["release_date"] : rest();
            isset($row["description"]) ? $newItem["description"] = $row["description"] : rest();
    }
        $arr["data"]=$newItem;
    echo json_encode($arr);
?>