<?php
    class App{

        protected $controller="home";
        protected $action="SayHi";
        protected $params;



        function __construct()
        {
            //Array ( [0] => home [1] => action [2] => 123 )
            $arr = $this->UrlProcess();

            //Controller handling
            if(file_exists("./MVC/Controllers/".$arr[0].".php"))
            {
                $this->controller = $arr[0];
                unset($arr[0]);
            }
            require_once "./MVC/Controllers/".$this->controller.".php";
            $this->controller = new $this->controller;

            //Action handling
            if(isset($arr[1]))
            {
                if( method_exists($this->controller,  $arr[1]) ){
                    $this->action = $arr[1];
                }
                unset($arr[1]);
            }
            
            //Params handling
            $this->params = $arr ? array_values($arr) : [];
            
            call_user_func_array([$this->controller, $this->action], $this->params);

        }

        function UrlProcess()
        {
            if(isset($_GET["url"])){
                return explode("/", filter_var(trim($_GET["url"], "/")));
            } 
        }

    }
?>