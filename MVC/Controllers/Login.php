<?php

header('Content-Type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Methods: PUT, GET, POST");
    header("Access-Control-Allow-Headers: X-Requested-With");
    class Login extends Controller
    {

        private $DB;
        function __construct()
        {
            $this->DB= new DB;
        }
        function LoginUser()
        {   
            $user ="";
            $password = "";
            
            $idCheck="";
            $userCheck="";
            $passwordCheck="";


            $data = json_decode(file_get_contents('php://input'), true);
            if($_SERVER['REQUEST_METHOD'] == "POST"){
                if( empty($data["customer_email"])){
                    $this->api("api-login", [
                        "login-status" => false,
                        "login-error" =>"User is required ..."
                    ]);
                }else{
                    $user = $data["customer_email"];
                }
                if( empty($data["customer_password"])){
                    $this->api("api-login", [
                        "login-status" => false,
                        "login-error" =>"Password is required"
                    ]);
                }else{
                    $password = $data["customer_password"];
                }
                $result = $this->model("LoginModel")->getLogin($user);
                while($row= mysqli_fetch_assoc($result)){
                    $idCheck = $row["customer_id"];
                    $userCheck = $row["customer_email"];
                    $passwordCheck = $row["customer_password"];
                }
                if($user===$userCheck){
                    if($password===$passwordCheck){
                        $this->api("api-login", [
                            "login-status" => true,
                            "customer_id"=> $idCheck,
                            "login-error" =>"None"
                        ]);
                    }else{
                        $this->api("api-login", [
                            "login-status" => false,
                            "login-error" => "Wrong Password"
                        ]);
                    }
                }else{
                    $this->api("api-login", [
                        "login-status" => false,
                        "login-error" =>"Wrong User Name"
                    ]);
                }
            }
        }
        function CheckLogIn(){
            $arr=array();
            $data = json_decode(file_get_contents('php://input'), true);
            $idCheck = (int)$data["id_log_in"];
            $id = $this->model("LoginModel")->getId($data["id_log_in"]);
            if($idCheck==0){
                $arr[1]["login-status"]= false;
                    echo json_encode($arr);
            }else{
                if($id==$idCheck){
                    $arr[1]["login-status"]= true;
                    echo json_encode($arr);
               }else{
                $arr[1]["login-status"]= false;
                    echo json_encode($arr);
               }
            }
        }
        function TestImg(){
            
            $file = $_FILES['image']['tmp_name'];
            echo $file."<br/>";
            $image = addslashes(file_get_contents($_FILES['image']['tmp_name']));
            $imgName = addslashes($_FILES['image']['name']);
            echo $imgName;
            $imgSize = getimagesize($_FILES['image']['tmp_name']);
            $des = "http://localhost/Cinema/Public/Imgs/";
            if(move_uploaded_file($file, $des)) {
                echo "The file ". basename( $_FILES['image']['name']). " has been uploaded";
                } else{
                echo "There was an error uploading the file, please try again!";
                }
        }
    }
?>
