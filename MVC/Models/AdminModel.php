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
                    return 1;
                }else{
                    return 2;
                }
            }
            return 3;
            mysqli_close($this->conn);
        }

        public function checkUser($user_name)
        {
            $qr="select admin_password from admin where admin_email='".$user_name."'";
            $result= mysqli_query($this->conn,$qr);
            $password=mysqli_fetch_assoc($result)["admin_password"];

            return $password;
        }

        public function createNewActor(
            $actor_name,
            $actor_name_banner,
            $location,
            $avatar,
            $banner,
            $date_of_birth,
            $occupation,
            $biography
        ){
            $qr="
                insert into cinema.actor 
                (
                    actor_name, 
                    actor_name_banner, 
                    avatar_small_url, 
                    avatar_banner_url, 
                    date_of_birth, 
                    location, 
                    occupation, 
                    biography
                )
                value
                ( 
                    '".$actor_name."', 
                    '".$actor_name_banner."', 
                    '".$avatar."', 
                    '".$banner."', 
                    '".$date_of_birth."', 
                    '".$location."', 
                    '".$occupation."', 
                    '".$biography."'
                );   
            ";
            if(mysqli_query($this->conn,$qr)){
                mysqli_close($this->conn);
                return true;
            }else{
                mysqli_close($this->conn);
                return false;
            }
        }

        public function createNewShowTime()
        {
            $qr="
                insert into show_time
                (
                    province_id,
                    cinema_id,
                    room_id,
                    movie_id,
                    show_time_date,
                    show_time_end
                ) value
                (
                    1,
                    1,
                    26,
                    20,
                    '2021-04-24 10:30',
                    DATE_ADD('2021-04-24 10:30', interval
                         (select duration from movie where movie_id=20) minute
                    )
                );
            ";
        }

        public function createNewSeateStatus(

        )
        {

        }

        public function createNewMovie(
            $movie_name,
            $movie_name_banner,
            $description,
            $avatar,
            $banner,
            $release,
            $language,
            $main_type,
            $country,
            $duration
        ){
            $check=null;
            $qr="
                insert into cinema.movie
                (
                    movie_name,
                    movie_name_banner,
                    description,
                    release_date,
                    banner_url,
                    avatar_url,
                    language,
                    main_type,
                    country,
                    duration
                )
                value
                (
                    '".$movie_name."',
                    '".$movie_name_banner."',
                    '".$description."',
                    '".$release."',
                    '".$banner."',
                    '".$avatar."',
                    '".$language."',
                    '".$main_type."',
                    '".$country."',
                    ".$duration."
                );
            ";
            if(mysqli_query($this->conn,$qr)){
                mysqli_close($this->conn);
                return true;
            }else{
                mysqli_close($this->conn);
                return false;
            }
        }
    }