<?php
    class MovieModel extends DB{
        public function getMovieBanner($id)
        {
            $qr = "SELECT movie_id,movie_name_banner,banner_url  FROM movie WHERE movie_id=".$id."";
            return mysqli_query($this->conn,$qr);
        }

        public function getMovieItem($id){
            $qr = "SELECT movie_id, movie_name, avatar_url, main_type, release_date, duration
                FROM movie WHERE movie_id=".$id;
                return mysqli_query($this->conn,$qr);
        }

        public function getMovieItemsSortByViewOpening($number){
            $qr = "SELECT movie_id, movie_name, avatar_url, main_type, release_date, duration
                FROM movie where release_date<'2021-04-24' order by views desc limit ".$number.";";
                if(mysqli_query($this->conn,$qr)){
                    // echo "success";
                }
                return mysqli_query($this->conn,$qr);
        }

        public function getMovieItemsSortByViewAndNameOpening($number, $input){
            $qr = "SELECT movie_id, movie_name, avatar_url, main_type, release_date, duration
                FROM movie where release_date<'2021-04-24' and movie_name like '%".$input."%'
                order by views desc limit ".$number.";";
                if(mysqli_query($this->conn,$qr)){
                    // echo "success";
                }
                return mysqli_query($this->conn,$qr);
        }


        public function getMovieItemsSortByViewComing($number){
            $qr = "SELECT movie_id, movie_name, avatar_url, main_type, release_date, duration
                FROM movie where release_date>'2021-04-24' limit ".$number.";";
                if(mysqli_query($this->conn,$qr)){
                    // echo "success";
                }
                return mysqli_query($this->conn,$qr);
        }



        public function getMovieBannerById($id){
            $qr = "SELECT movie_id,movie_name_banner,banner_url  FROM movie WHERE movie_id=".$id."";
            return mysqli_query($this->conn,$qr);
        }

        public function getMovieDetailsById($id){
            $qr = "SELECT country, language, release_date , description FROM movie WHERE movie_id=".$id."";
            return mysqli_query($this->conn,$qr);
        }

        public function getMovieItemsByActorId($id, $limit){
            $qr1 = "SELECT movie_id FROM movie_has_actor WHERE actor_id=".$id." limit ".$limit;
            $qrPush = "";
            $result= mysqli_query($this->conn,$qr1);
            while($row = mysqli_fetch_assoc($result)){
                $qrPush = $qrPush."movie_id=".$row["movie_id"]." or ";
            }
            if($qrPush!=""){
                $qr2 = "SELECT * FROM movie WHERE ".$qrPush;
                $qr2 =substr($qr2, 0, -3);
            }else{
                $qr2 = "SELECT * FROM movie WHERE  movie_id=0";
            }
            return mysqli_query($this->conn,$qr2);
        }

        public function getMovieLike($customer_id,$id)
        {
            $qr = "select count(*) from customer_has_like_movies where customer_id=".$customer_id." and movie_id=".$id.";";
            return (mysqli_query($this->conn,$qr));
        }

        public function getActors($id)
        {
            $qr1="select actor_id from movie_has_actor where movie_id=".$id;
            $qrPush="";
            $result= mysqli_query($this->conn,$qr1);
            while($row = mysqli_fetch_assoc($result))
            {
                $qrPush=$qrPush."actor_id=".$row["actor_id"]." or ";
            }
            
            if($qrPush!=""){
                $qr2 = "SELECT actor_id,actor_name from actor where ".$qrPush;
                $qr2 =substr($qr2, 0, -3);
            }else{
                $qr2 = "SELECT actor_id,actor_name from actor where actor_id=0";
            }
            return mysqli_query($this->conn,$qr2);
        }
    }
?>