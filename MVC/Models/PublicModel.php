<?php
    class PublicModel extends DB{
        public function getProvince()
        {
            $qr = "select * from province";
            return mysqli_query($this->conn,$qr);
        }
        public function getCinema($province_id)
        {
            $qr = "select cinema_id, cinema_name from cinema where province_id=".$province_id;
            return mysqli_query($this->conn,$qr);
        }
        public function getShowTime($cinema, $movie ,$time){
            $qr="
                select show_time_id, 
                (select room_name from room where show_time.room_id = room.room_id) as room_name,
                room_id, 
                show_time_date 
                from show_time
                where
                    cinema_id=".$cinema."
                    and
                    movie_id=".$movie."
                    and
                    show_time_date>'".$time."'
                    and
                    show_time_date<DATE_ADD('".$time."', interval 1 day)
                ;
            ";
            return mysqli_query($this->conn,$qr);
        }
    }
?>