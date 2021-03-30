<?php
    class AdminModel extends DB{
        public function createNewRoom($cinema_id, $room_type_id, $seat_number)
        {
            $id=0;
            $arr=array();
            $qr1="select COUNT(room_id) from room where cinema_id=".$cinema_id." order by room_id desc limit 1;";
            $result1= mysqli_query($this->conn,$qr1);
            $number=mysqli_fetch_assoc($result1)["COUNT(room_id)"];
            // echo $number;
            $qr2 = "
                INSERT INTO room (room_name, room_type_id, cinema_id) VALUES
                (
                    'Room".($number+1)."',
                    ".$room_type_id.",
                    ".$cinema_id."
                );
            ";
            if (mysqli_query($this->conn,$qr2)) {
                $qr3= "select room_id from room where cinema_id=".$cinema_id." and room_name='Room".($number+1)."'";
                $result2= mysqli_query($this->conn,$qr3);
                $id= mysqli_fetch_assoc($result2)["room_id"];
                $qr4="INSERT INTO seat (seat_name, room_id) VALUES";
                for ($i = 1; $i <= $seat_number; $i++) {
                    $qr4 =$qr4."
                        (
                            'A".$i."',
                            ".$id."
                        ),"; 
                }
                $qr4=substr($qr4,0,-1).";";
                // echo $qr4;
                if(mysqli_query($this->conn,$qr4)){
                    $arr["status"]="New record created successfully";
                    echo json_encode($arr);
                }else{
                    $arr["status"]="seat cant not create";
                    $arr["error"]= mysqli_error($this->conn);
                    echo json_encode($arr);
                    // echo mysqli_error($this->conn);
                }
            }else {
                $arr["status"]="room cant not create";
                $arr["error"] = $qr2."<br>". mysqli_error($this->conn);
                echo json_encode($arr);
            }
            mysqli_close($this->conn);
        }
    }
?>