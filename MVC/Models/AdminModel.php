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

        public function getAllAccount()
        {
            $qr="
                select admin_id, admin_role, admin_email, admin_password, admin_name, admin_number from admin;
            ";
            return mysqli_query($this->conn,$qr);
        }

        public function checkUser($user_name,$password)
        {
            $role=0;
            $qr="
                select admin_role from admin where admin_email='".$user_name."' and admin_password='".$password."';
            ";
            $result= mysqli_query($this->conn,$qr);
            $role=mysqli_fetch_assoc($result)["admin_role"];
            return $role;
        }

        public function createNewAccount(
            $admin_user_name,
            $admin_password,
            $admin_role,
            $admin_name,
            $admin_number_phone
        )
        {
            $qr="
                insert into admin
                (
                    admin_role, admin_email, admin_password, cinema_id, admin_name, admin_number
                )
                    value
                (
                    ".$admin_role.",
                    '".$admin_user_name."',
                    '".$admin_password."',
                    1,
                    '".$admin_name."',
                    '".$admin_number_phone."'
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

        public function getShowTime(
            $province_id,
            $cinema_id,
            $date
        )
        {
            $qr="
                select show_time_id,
                    room_id,
                    (select room_name from room r where r.room_id=show_time.room_id) as room_name,
                    show_time_date from show_time where
                          province_id=".$province_id." and
                          cinema_id=".$cinema_id." and
                          show_time_date>'".$date."' and
                          show_time_date<DATE_ADD('".$date."', interval 1 day)
                    ;
            ";
            return  mysqli_query($this->conn,$qr);
        }

        public function getAllTicket()
        {
            $qr="
                select
                    ticket_id,
                    (select province_name from province where province.province_id=ticket.province_id)
                           as province_name,
                    (select cinema_name from cinema where cinema.cinema_id=ticket.cinema_id)
                           as cinema_name,
                    (select room_name from room where room.room_id=ticket.room_id)
                           as room_name,
                    (select customer_name from customer where ticket.customer_id=customer.customer_id)
                           as customer_name,
                    (select show_time_date from show_time where ticket.show_time_id=show_time.show_time_id)
                           as show_time_date,
                    DATE_ADD(
                            (select show_time_date from show_time where ticket.show_time_id=show_time.show_time_id)
                        , interval
                            (select duration from movie where movie_id=ticket.movie_id) minute
                        )
                           as show_time_end,
                       ticket_date,
                    (select movie_name from movie where movie.movie_id=ticket.movie_id)
                           as movie_name,
                    100000 as ticket_price,
                    (select avatar_url from movie where movie.movie_id=ticket.movie_id)
                            as avatar_url,
                    (select seat_name
                     from seat
                     where seat_id = (
                         select seat_id
                         from seat_status_on_show_time
                         where seat_status_on_show_time_id = ticket.seat_status_on_show_time_id)
                    ) as seat_name
                from ticket order by ticket_date DESC;
            ";
            return mysqli_query($this->conn,$qr);
        }

        public function getAllTicketFromTo(
            $time_start,
            $time_end,
            $province_id,
            $cinema_id
        )
        {
            $qr_province = "";
            $qr_cinema = "";

            if(
                $province_id !== "0" &&
                $province_id !== 0
            ){
                $qr_province = "and province_id=".$province_id;
                if(
                    $cinema_id !== "0" &&
                    $cinema_id !== 0
                ){
                    $qr_cinema = "and cinema_id=".$cinema_id;
                }
            }
            $qr="
                select
                    ticket_id,
                    (select province_name from province where province.province_id=ticket.province_id)
                           as province_name,
                    (select cinema_name from cinema where cinema.cinema_id=ticket.cinema_id)
                           as cinema_name,
                    (select room_name from room where room.room_id=ticket.room_id)
                           as room_name,
                    (select customer_name from customer where ticket.customer_id=customer.customer_id)
                           as customer_name,
                    (select show_time_date from show_time where ticket.show_time_id=show_time.show_time_id)
                           as show_time_date,
                    DATE_ADD(
                            (select show_time_date from show_time where ticket.show_time_id=show_time.show_time_id)
                        , interval
                            (select duration from movie where movie_id=ticket.movie_id) minute
                        )
                           as show_time_end,
                       ticket_date,
                    (select movie_name from movie where movie.movie_id=ticket.movie_id)
                           as movie_name,
                    100000 as ticket_price,
                    (select avatar_url from movie where movie.movie_id=ticket.movie_id)
                            as avatar_url,
                    (select seat_name
                     from seat
                     where seat_id = (
                         select seat_id
                         from seat_status_on_show_time
                         where seat_status_on_show_time_id = ticket.seat_status_on_show_time_id)
                    ) as seat_name
                from ticket
                 where ticket_date>'".$time_start."'
                   and ticket_date<'".$time_end."'
                   ".$qr_province."
                   ".$qr_cinema."
                 order by ticket_date;
            ";
            return mysqli_query($this->conn,$qr);
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

        public function createSeatStatus(
            $show_time_date,
            $room_id
        )
        {
            $qr="
                insert into seat_status_on_show_time
                    (
                        seat_id,
                        seat_status,
                        show_time_id
                    )
                    select seat_id,
                    1 as seat_status,
                    (
                        select show_time_id from show_time where
                               show_time_date='".$show_time_date."' and
                               room_id=".$room_id."
                    ) as show_time_id
                    from seat where room_id =".$room_id."
                ;
            ";
            if(mysqli_query($this->conn,$qr)){
                mysqli_close($this->conn);
                return true;
            }else{
                mysqli_close($this->conn);
                return false;
            }
        }

        public function deleteShowTime(
            $show_time_date,
            $room_id
        )
        {
            $qr="
                delete from show_time
                where
                    show_time_date='".$show_time_date."' and
                    room_id=".$room_id.";
            ";
            if(mysqli_query($this->conn,$qr)){
                mysqli_close($this->conn);
                return true;
            }else{
                mysqli_close($this->conn);
                return false;
            }
        }

        public function createNewShowTime(
            $province_id,
            $cinema_id,
            $room_id,
            $movie_id,
            $show_time_date
        )
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
                    ".$province_id.",
                    ".$cinema_id.",
                    ".$room_id.",
                    ".$movie_id.",
                    '".$show_time_date."',
                    DATE_ADD('".$show_time_date."', interval
                         (select duration from movie where movie_id=".$movie_id.") minute
                    )
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

        public function checkProvinceExist($province_name)
        {
            $qr="
                SELECT Count(*) as exist FROM province where province.province_name like '%".$province_name."%'
            ";
            $result= mysqli_query($this->conn,$qr);
            $isExist=mysqli_fetch_assoc($result)["exist"];
            return $isExist;
        }

        public function checkCinemaExist($cinema_name)
        {
            $qr="
                SELECT Count(*) as exist FROM cinema where cinema.cinema_name like '%".$cinema_name."%'
            ";
            $result= mysqli_query($this->conn,$qr);
            $isExist=mysqli_fetch_assoc($result)["exist"];
            return $isExist;
        }

        public function checkMovieExist($movie_name)
        {
            $qr="
                SELECT Count(*) as exist FROM movie where movie.movie_name like '%".$movie_name."%'
            ";
            $result= mysqli_query($this->conn,$qr);
            $isExist=mysqli_fetch_assoc($result)["exist"];
            return $isExist;
        }

        public function checkActorExist($actor_name)
        {
            $qr="
                SELECT Count(*) as exist FROM actor where actor.actor_name like '%".$actor_name."%'
            ";
            $result= mysqli_query($this->conn,$qr);
            $isExist=mysqli_fetch_assoc($result)["exist"];
            return $isExist;
        }

        public function checkNewShowtime(
            $room_id,
            $movie_id,
            $show_time_date
        )
        {
            $qr="
                select COUNT(*)
                from show_time
                where
                    room_id = ".$room_id." and
                    '".$show_time_date."'>=show_time_date and
                    '".$show_time_date."'<=show_time_end
                or
                    room_id = ".$room_id." and
                    DATE_ADD('".$show_time_date."', interval
                        (select duration from movie where movie_id=".$movie_id.") minute )
                            >=show_time_date
                        and
                    DATE_ADD('".$show_time_date."', interval
                        (select duration from movie where movie_id=".$movie_id.") minute )
                            <=show_time_end
                or
                    room_id = ".$room_id." and
                    '".$show_time_date."' <= show_time_date and
                    DATE_ADD('".$show_time_date."', interval
                             (select duration from movie where movie_id=".$movie_id.") minute )
                            >=show_time_end
                ;
            ";
            $result= mysqli_query($this->conn,$qr);
            $check=mysqli_fetch_assoc($result)["COUNT(*)"];
            return $check;

        }

        public function createNewSeatStatus(

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