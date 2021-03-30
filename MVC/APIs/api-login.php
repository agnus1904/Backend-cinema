<?php
        $arr = array();
        if($data["login-status"]==true){
            $arr["status"] = true;
            $arr["customer_id"] = $data["customer_id"];
            $arr["error"] = "";
            $arr["data"] = "data";
        }else{
            $arr["status"] = false;
            $arr["error"] = $data["login-error"];
            $arr["data"] = "";
        }
        echo json_encode($arr);
?>