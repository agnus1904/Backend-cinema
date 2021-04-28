<?php
    class Controller{

        public function model($model)
        {
            require_once "./MVC/Models/".$model.".php";
            return new $model;
        }

        public function api($api, $data=[])
        {
            require_once "./MVC/APIs/".$api.".php";
        }

        public function Home()
        {
            require_once "./Public/Home/index.html";
        }

        public function Admin()
        {
            require_once "./Public/Admin/index.html";
        }

    }
?>