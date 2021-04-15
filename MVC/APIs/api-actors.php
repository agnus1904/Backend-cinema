<?php
        function rest(){
            ;
        }
        $arr = array();
        $index=0;
        $newItem=[];
        while($row = mysqli_fetch_assoc($data["actors"])){
            isset($row["actor_id"])?
                $newItem[$index]["actor_id"] = $row["actor_id"] :rest();
            isset($row["actor_name"])?
                $newItem[$index]["actor_name"] = $row["actor_name"] :rest();
            isset($row["actor_name_banner"])?
                $newItem[$index]["actor_name_banner"] = $row["actor_name_banner"] :rest();
            isset($row["avatar_small_url"])?
                $newItem[$index]["avatar_small_url"] = $row["avatar_small_url"] :rest();
            isset($row["avatar_banner_url"])?
                $newItem[$index]["avatar_banner_url"] = $row["avatar_banner_url"] :rest();
            isset($row["date_of_birth"])?
                $newItem[$index]["date_of_birth"] = $row["date_of_birth"] :rest();
            isset($row["location"])?
                $newItem[$index]["location"] = $row["location"] :rest();
            isset($row["occupation"])?
                $newItem[$index]["occupation"] = $row["occupation"] :rest();
            isset($row["biography"])?
                $newItem[$index]["biography"] = $row["biography"] :rest();
            isset($row["actor_id"])?
                $newItem[$index]["actor_id"] = $row["actor_id"] :rest();

            $index++;
        }

        $arr["data"]=$newItem;

        echo json_encode($arr);
?>