<?php

        $index=0;
        $newItem=[];
        while($row = mysqli_fetch_assoc($data["movie_banner"])){
            $newItem[$index]["movieId"] = $row["movie_id"];
            $newItem[$index]["movieName"] = $row["movie_name"];
            $newItem[$index]["movieNameBanner"] = $row["movie_name_banner"];
            $newItem[$index]["description"] = $row["description"];
            $newItem[$index]["banner_url"]  = $row["banner_url"];
            $newItem[$index]["avatarUrl"]  = $row["avatar_url"];
            $newItem[$index]["liked"]  = $row["liked"];
            $newItem[$index]["mainType"]  = $row["main_type"];
            $index++;
            $arr[1]=$newItem;
        }
        echo json_encode($arr);
?>