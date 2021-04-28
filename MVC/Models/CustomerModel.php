<?php
    class CustomerModel extends DB{

        public function checkCusomerEmail($email){
            $qr="select COUNT(*)
                from customer
                where customer_email='".$email."';
            ";
            $result1= mysqli_query($this->conn,$qr);
            $count=mysqli_fetch_assoc($result1)["COUNT(*)"];
            return $count;
        }

        public function createNewCustomer(
            $customerEmail,
            $customerPassword,
            $customerFullName,
            $customerNumberPhone
        ){
            $arr=array();
            $qr="
                insert into customer
                (
                    customer_email,
                    customer_password,
                    customer_name,
                    customer_number_phone
                ) value
                (
                    '".$customerEmail."',
                    '".$customerPassword."',
                    '".$customerFullName."',
                    '".$customerNumberPhone."'
                );
            ";
            if(mysqli_query($this->conn,$qr)){
                $arr["status"]= true;
                $arr["error"]= null;
                return $arr;
            }else{
                $arr["status"]="Cant not create";
                $arr["error"]= mysqli_error($this->conn);
                return $arr;
            }
        }

        public function changeSeatStatusTo2(
            $seat_status_on_show_time_id
        ){
            $qr="
                update seat_status_on_show_time
                set seat_status=2
                where seat_status_on_show_time_id=".$seat_status_on_show_time_id.";
            ";
            if(mysqli_query($this->conn,$qr)){
                mysqli_close($this->conn);
                return true;
            }else{
                mysqli_close($this->conn);
                return false;
            }
        }

        public function changeSeatStatusBackToOne(
            $seat_status_on_show_time_id
        ){
            $qr="
                update seat_status_on_show_time
                set seat_status=1
                where seat_status_on_show_time_id=".$seat_status_on_show_time_id.";
            ";
            if(mysqli_query($this->conn,$qr)){
                mysqli_close($this->conn);
                return true;
            }else{
                mysqli_close($this->conn);
                return false;
            }
        }

        public function changeSeatStatusTo3(
            $seat_status_on_show_time_id
        )
        {
            $qr="
                update seat_status_on_show_time
                set seat_status=3
                where seat_status_on_show_time_id=".$seat_status_on_show_time_id.";
            ";
            if(mysqli_query($this->conn,$qr)){
                mysqli_close($this->conn);
                return true;
            }else{
                mysqli_close($this->conn);
                return false;
            }
        }

        public function getMovieAvatar($movie_id)
        {
            $qr="
                select avatar_url from movie where movie_id=".$movie_id.";
            ";
            $result= mysqli_query($this->conn,$qr);
            $avatar_url=mysqli_fetch_assoc($result)["avatar_url"];
            return $avatar_url;
        }

        public function getTicketsByCustomerId($customer_id)
        {
            $qr="
                select
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
                from ticket where customer_id=".$customer_id." ORDER BY ticket_date DESC;
            ";
            return mysqli_query($this->conn,$qr);
        }



        public function createNewTicket(
            $province_id,
            $cinema_id,
            $customer_id,
            $show_time_id,
            $movie_id,
            $price,
            $show_time_date,
            $ticket_status,
            $seat_status_on_show_time
        )
        {
            $qr="
                insert into ticket
                    (
                        province_id,
                        cinema_id,
                        room_id,

                        customer_id,
                        show_time_id,
                        movie_id,

                        price,
                        ticket_date,
                        ticket_status,

                        seat_status_on_show_time_id,
                        seat_name
                    )
                 value
                    (
                        ".$province_id.",
                        ".$cinema_id.",
                        (select room_id from show_time where show_time_id=".$show_time_id."),

                        ".$customer_id.",
                        ".$show_time_id.",
                        ".$movie_id.",

                        ".$price.",
                        '".$show_time_date."',
                        ".$ticket_status.",

                        ".$seat_status_on_show_time.",
                        (select seat_name from seat
                        where seat_id=(
                                select seat_id from seat_status_on_show_time
                                where seat_status_on_show_time_id=".$seat_status_on_show_time."
                            )
                        )
                    );
            ";


            if(mysqli_query($this->conn,$qr)){
                mysqli_close($this->conn);
                return true;
            }else{
                echo mysqli_error($this->conn);
                mysqli_close($this->conn);
                return false;
            }
        }


        public function getMovieBanner()
        {
            $qr = "SELECT movie_id,movie_name_banner,banner_url  FROM movie WHERE movie_id=20";
            return mysqli_query($this->conn,$qr);
        }
    }
?>