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
        public function getRoom($cinema_id)
        {
            $qr = "select room_id, room_name,
            (select count(*) from seat where seat.room_id= room.room_id) as seat_number
            from room where cinema_id=".$cinema_id;
            return mysqli_query($this->conn,$qr);
        }

        public function getAllMovie()
        {
            $qr="select movie_id, movie_name from movie;";
            return mysqli_query($this->conn,$qr);
        }

        public function getAllMovieByName($input)
        {
            $qr = "SELECT movie_id, movie_name FROM movie where movie_name like '%".$input."%';";
            return mysqli_query($this->conn,$qr);
        }

        public function getAllActor()
        {
            $qr="select actor_id, actor_name from actor;";
            return mysqli_query($this->conn,$qr);
        }

//         public function getCountActor()
//         {
//             $qr="select count(actor_id) as number from actor";
//             return mysqli_query($this->conn,$qr);
//         }

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

        public function getShowTimeByRoom($room, $movie ,$time){
                    $qr="
                        select show_time_id,
                        (select room_name from room where show_time.room_id = room.room_id) as room_name,
                        room_id,
                        show_time_date,
                        show_time_end
                        from show_time
                        where
                            room_id=".$room."
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