<?php

header('Content-Type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Methods: PUT, GET, POST");
    header("Access-Control-Allow-Headers: X-Requested-With");
    class Actor extends Controller
    {
        function GetActorBannerById($id)
        {   
            $actors = $this->model("ActorModel");
            $this->api("api-actor", [
                "actor" => $actors->getActorBannerById($id),
                "type" => "banner"
            ]);
        }

        function GetActorDetailsById($id)
        {
            $actors = $this->model("ActorModel");
            $this->api("api-actor", [
                "actor" => $actors->getActorDetailsById($id),
                "type" => "details"
            ]);
        }

        function GetActorByMovieId($movie_id){
            $actors = $this->model("ActorModel");
            $this->api("api-actors",[
                "actors" => $actors->getActorByMovieId($movie_id)
            ]);
        }

        function GetActorImagesById($id)
        {
            $actors = $this->model("ActorModel");
            $this->api("api-actor", [
                "actor" => $actors->getActorImagesById($id),
                "type" => "images"
            ]);
        }


        function GetActorContent($id){
            $actorUrl = $this->model("ActorModel");
            $firms = $this->model("ActorModel");
            $actor = $this->model("ActorModel");

            $this->api("api-actor-content", [
                "links" => $actorUrl->getImgs($id),
                "items" => $firms->getFirms($id),
                "actor" => $actor->getActor($id)
            ]);
        }
    }
?>