<?php
    class HomeModel extends DB{
        public function getMovieBanner()
        {
            $qr = "SELECT movie_id,movie_name_banner,banner_url  FROM movie WHERE movie_id=20";
            return mysqli_query($this->conn,$qr);
        }
        public function getMovieContentOpening()
        {
            $qr = "SELECT movie_id, movie_name, avatar_url, liked, main_type, release_date FROM movie WHERE movie_id>=9 and movie_id<=16";
            return mysqli_query($this->conn,$qr);
        }
        public function getMovieContentComing()
        {
            $qr = "SELECT movie_id, movie_name, avatar_url, liked, main_type, release_date FROM movie WHERE movie_id>=17";
            return mysqli_query($this->conn,$qr);
        }
    }
?>