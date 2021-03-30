<?php
    class ActorModel extends DB{

        public function getActorBannerById($id)
        {
            $qr = "SELECT actor_id,  actor_name_banner, avatar_banner_url FROM actor WHERE actor_id=".$id."";
            return mysqli_query($this->conn,$qr);
        }
        public function getActorDetailsById($id)
        {
            $qr = "SELECT actor_id,  avatar_small_url, date_of_birth, location, occupation, biography FROM actor WHERE actor_id=".$id."";
            return mysqli_query($this->conn,$qr);
        }

        public function getActorByMovieId($movie_id)
        {
            $qr1="select actor_id from movie_has_actor where movie_id=".$movie_id;
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

        public function getActorImagesById($id)
        {
            $qr = "SELECT actor_img_url FROM actor_img WHERE actor_id=".$id."";
            return mysqli_query($this->conn,$qr);
        }

        public function getFirmItemsBy($id){
            $qr1 = "SELECT movie_id FROM movie_has_actor WHERE actor_id=".$id."";
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
    }
?>