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

    }
?>