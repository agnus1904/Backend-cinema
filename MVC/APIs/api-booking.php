<?php
        $arr = array();
        if($data["login-status"]==true){
            $arr["status"] = true;
            // $arr["id"] = $data["id"];
            // $arr["error"] = "";
            // $arr["data"] = "data";
        }else{
            $arr["status"] = false;
            // $arr["error"] = $data["login-error"];
            // $arr["data"] = "";
        }
        echo json_encode($arr);
?>