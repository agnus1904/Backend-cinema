<?php

header('Content-Type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Methods: PUT, GET, POST");
    header("Access-Control-Allow-Headers: X-Requested-With");
    class Customer extends Controller
    {
        function Register()
        {   
            $arr = array();
            $customerEmail=$customerPassword=$customerFullName=$customerNumberPhone="";
            $customerEmailErr=$customerPasswordErr=$customerFullNameErr=$customerNumberPhoneErr="";
            $data = json_decode(file_get_contents('php://input'), true);
            if($_SERVER['REQUEST_METHOD'] == "POST"){
                // user name
                if(isset($data["customer_email"]) || $data["customer_email"]!=""){
                    $customerEmail= $data["customer_email"];
                    $customerEmailErr="";
                }else{
                    $customerEmailErr="User Name is required";
                    $arr["status"]= false;
                    $arr["error"]= $customerEmailErr;
                    echo json_encode($arr);
                    return;
                }
                // password 
                if(isset($data["customer_password"]) || $data["customer_password"]!=""){
                    $customerPassword =  $data["customer_password"];
                    $customerPasswordErr="";
                }else{
                    $customerPasswordErr="Password Name is required";
                    $arr["status"]= false;
                    $arr["error"]= $customerPasswordErr;
                    echo json_encode($arr);
                    return;
                }
                // full name 
                if(isset($data["customer_fullname"]) || $data["customer_fullname"]!=""){
                    $customerFullName =  $data["customer_fullname"];
                    $customerFullNameErr="";
                }else{
                    $customerFullNameErr="Full Name is required";
                    $arr["status"]= false;
                    $arr["error"]= $customerFullNameErr;
                    echo json_encode($arr);
                    return;
                }
                if(isset($data["customer_number_phone"]) || $data["customer_number_phone"]!=""){
                    $customerNumberPhone=  $data["customer_number_phone"];
                    $customerNumberPhoneErr="";
                }else{
                    $customerNumberPhoneErr="Number Phone is required";
                    $arr["status"]= false;
                    $arr["error"]= $customerNumberPhoneErr;
                    echo json_encode($arr);
                    return;
                }
                if($customerEmailErr=="" && $customerPasswordErr== "" && $customerFullNameErr==""){
                    $count = $this->model("CustomerModel")->checkCusomerEmail($customerEmail);
                    if($count==0){
                        $createNew = $this->model("CustomerModel")->createNewCustomer(
                            $customerEmail,
                            $customerPassword,
                            $customerFullName,
                            $customerNumberPhone
                        );
                        if($createNew["status"]==true){
                            $arr["status"]= true;
                            $arr["error"]= null;
                            echo json_encode($arr);
                            return;
                        }else{
                            $arr["status"]= false;
                            $arr["error"]= $createNew["error"];
                            echo json_encode($arr);
                            return;
                        }
                    }else{
                        $arr["status"]= false;
                        $arr["error"]= "Email have existed";
                        echo json_encode($arr);
                        return;
                    }
                }
            }
        }

        function ChangeSeatStatusBackToOne()
        {
            $response = array();
            $seat_status_on_show_time_id="";
            if($_SERVER['REQUEST_METHOD'] == "POST"){
                $seat_status_on_show_time_id=$_POST["seat_status_on_show_time_id"];
                $check_seat_status = $this->model("CustomerModel")->changeSeatStatusBackToOne(
                    $seat_status_on_show_time_id
                );
                if($check_seat_status===true){
                    $response = array(
                        "status" => "success",
                        "error" => false,
                        "message" => "Change back to 1 success"
                    );
                    echo json_encode($response);
                }else{
                    $response = array(
                        "status" => "error",
                        "error" => true,
                        "message" => "Change back to 1 false"
                    );
                    echo json_encode($response);
                }
            }
        }


        function ChangeSeatStatusToTwo()
        {
            $response = array();
            $seat_status_on_show_time_id="";
            if($_SERVER['REQUEST_METHOD'] == "POST"){
                $seat_status_on_show_time_id=$_POST["seat_status_on_show_time_id"];
                $check_seat_status = $this->model("CustomerModel")->changeSeatStatusTo2(
                    $seat_status_on_show_time_id
                );
                if($check_seat_status===true){
                    $response = array(
                        "status" => "success",
                        "error" => false,
                        "message" => "Change to 2 success"
                    );
                    echo json_encode($response);
                }else{
                    $response = array(
                        "status" => "error",
                        "error" => true,
                        "message" => "Change to 2 false"
                    );
                    echo json_encode($response);
                }
            }
        }

        function GetTicketsByCustomerId()
        {
            $response = array();
            $customer_id="";
            if($_SERVER['REQUEST_METHOD'] == "POST"){
                if(empty($_POST["customer_id"])){
                    $response = array(
                        "status" => "error",
                        "error" => true,
                        "message" => "Please Login to see ticket"
                    );
                    echo json_encode($response);
                }else{
                    $customer_id=$_POST["customer_id"];
                    $dataTicket = $this->model("CustomerModel")->getTicketsByCustomerId($customer_id);
                    $this->api("api-tickets",[
                        "tickets" =>  $dataTicket
                    ]);
                }
            }
        }

        function GetMovieAvatar()
        {
            $response = array();
            $movie_id="";
            if($_SERVER['REQUEST_METHOD'] == "POST"){
                if(empty($_POST["movie_id"])){
                    $response = array(
                        "status" => "error",
                        "error" => true,
                        "message" => "No Movie Id"
                    );
                    echo json_encode($response);
                }else{
                    $movie_id=$_POST["movie_id"];
                    $avatar_url = $this->model("CustomerModel")->getMovieAvatar($movie_id);
                    if(empty($avatar_url)){
                        $response = array(
                            "status" => "Warning",
                            "error" => false,
                            "message" => "http://localhost/Cinema/Public/Imgs/avatar_small/avatar_actor_default.png"
                        );
                        echo json_encode($response);
                    }else{
                        $response = array(
                            "status" => "success",
                            "error" => false,
                            "message" => $avatar_url
                        );
                        echo json_encode($response);
                    }
                }
            }
        }

        function CreateNewTicket()
        {
            $response = array();

            $province_id= "";
            $cinema_id = "";

            $customer_id="";
            $show_time_id="";
            $movie_id="";

            $price=100000;
            $show_time_date="";
            $ticket_status=1;

            $seat_status_on_show_time="";

            if($_SERVER['REQUEST_METHOD'] == "POST"){
                $province_id= $_POST["province_id"];
                $cinema_id = $_POST["cinema_id"];

                $customer_id= $_POST["customer_id"];
                $show_time_id= $_POST["show_time_id"];
                $movie_id= $_POST["movie_id"];

                $show_time_date= $_POST["show_time_date"];

                $seat_status_on_show_time= $_POST["seat_status_on_show_time_id"];

                if(
                    empty($province_id) ||
                    empty($cinema_id) ||

                    empty($customer_id) ||
                    empty($show_time_id) ||
                    empty($movie_id) ||

                    empty($show_time_date) ||

                    empty($seat_status_on_show_time)
                )
                {
                    $response = array(
                        "status" => "error",
                        "error" => true,
                        "message" => "Please Enter full of fill"
                    );
                    echo json_encode($response);

                }else{
                    $check_seat_status = $this->model("CustomerModel")->changeSeatStatusTo3(
                        $seat_status_on_show_time
                    );
                    if($check_seat_status===true){
                        $check= $this->model("CustomerModel")->createNewTicket(
                            $province_id,
                            $cinema_id,
                            $customer_id,
                            $show_time_id,
                            $movie_id,
                            $price,
                            $show_time_date,
                            $ticket_status,
                            $seat_status_on_show_time
                        );
                        if($check===true){
                            $response = array(
                                "status" => "success",
                                "error" => false,
                                "message" => "Create new Ticket Done"
                            );
                        }else{
                            $response = array(
                                "status" => "error",
                                "error" => true,
                                "message" => "Cant create a new Ticket"
                            );
                        }
                        echo json_encode($response);
                    }else{
                        $response = array(
                            "status" => "error",
                            "error" => true,
                            "message" => "This Seat Cant Book",
                            "Seat id"  => $seat_status_on_show_time
                        );
                        echo json_encode($response);

                    }
                }



            }

        }


        function CustomerChangeLikeMovie()
        {
            $like = "";
        }
        
    }
?>
