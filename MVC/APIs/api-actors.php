<?php

        $arr = array();
        $index=0;
        $newItem=[];

        while($row = mysqli_fetch_assoc($data["actors"])){
            $newItem[$index]["actor_id"] = $row["actor_id"];
            $newItem[$index]["actor_name"] = $row["actor_name"];
            $index++;
            $arr["data"]=$newItem;
        }
        echo json_encode($arr);
?>