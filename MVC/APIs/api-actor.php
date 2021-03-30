<?php

        $arr = array();
        $newItem = array();
        $index=0;
        while($row = mysqli_fetch_assoc($data["actor"])){
            if($data["type"]=="banner"){
                $newItem["actor_id"] = $row["actor_id"];
                $newItem["actor_name_banner"] = $row["actor_name_banner"];
                $newItem["avatar_banner_url"] = $row["avatar_banner_url"];
            }elseif($data["type"]=="details"){
                $newItem["actor_id"] = $row["actor_id"];
                $newItem["avatar_small_url"] = $row["avatar_small_url"];
                $newItem["date_of_birth"] = $row["date_of_birth"];
                $newItem["location"] = $row["location"];
                $newItem["occupation"] = $row["occupation"];
                $newItem["biography"] = $row["biography"];
            }elseif($data["type"]=="images"){
                $newItem[$index] = $row["actor_img_url"];
                $index++;
            }
        }
        $arr["data"] = $newItem;
        echo json_encode($arr);
?>