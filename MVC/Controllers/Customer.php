<?php

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

        function CustomerChangeLikeMovie()
        {
            $like = "";
        }
        
    }
?>
