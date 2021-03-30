<?php

    class Movie extends Controller
    {
        function GetMovieBanner($id)
        {   
            $movies = $this->model("MovieModel");
            $this->api("api-movie", [
                "movie" => $movies->getMovieBanner($id),
            ]);
        }
        function GetMovieItem($id)
        {   
            $actors = $this->model("MovieModel");
            $this->api("api-movie", [
                "movie" => $actors->getMovieItem($id),
            ]);
        }
        
        function GetMovieItemsSortByViewOpening($number){
            $movies = $this->model("MovieModel");
            $this->api("api-movies",[
                "movies" => $movies->getMovieItemsSortByViewOpening($number),
            ]);
        }

        function GetMovieItemsSortByViewAndNameOpening($number, $input){
            $movies = $this->model("MovieModel");
            $this->api("api-movies",[
                "movies" => $movies->getMovieItemsSortByViewAndNameOpening($number, $input),
            ]);
        }

        function GetMovieItemsSortByViewComing($number){
            $movies = $this->model("MovieModel");
            $this->api("api-movies",[
                "movies" => $movies->getMovieItemsSortByViewComing($number),
            ]);
        }



        function GetMovieBannerById($id)
        {
            $movie= $this->model("MovieModel");
            $this->api("api-movie",[
                "movie" => $movie->getMovieBannerById($id),
            ]);
        }
        function GetMovieDetailsById($id)
        {
            $movie= $this->model("MovieModel");
            $this->api("api-movie",[
                "movie" => $movie->getMovieDetailsById($id),
            ]);
        }

        function GetMovieItemsByActorId($id,$limit)
        {
            $movies = $this->model("MovieModel");
            $this->api("api-movies", [
                "movies" => $movies->getMovieItemsByActorId($id,$limit),
            ]);
        }

        function GetMovieLike($customer_id, $id)
        {
            $arr=array();
            $like = $this->model("MovieModel")->getMovieLike($customer_id, $id);
            $like=mysqli_fetch_assoc($like)["count(*)"];
            $arr["data"]["like"]=$like;
            echo json_encode($arr);
        }
    }
?>

