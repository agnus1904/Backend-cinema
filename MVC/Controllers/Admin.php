<?php
header('Content-Type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Methods: PUT, GET, POST");
    header("Access-Control-Allow-Headers: X-Requested-With");
    class Admin extends Controller 
    {
        private $DB;

        function __construct()
        {
            $this->DB= new DB;

        }

        function TestBooking(){
            $arr=array();
            $qr = "SELECT * FROM booking_test";
            $result= mysqli_query($this->DB->conn,$qr);
            $row =mysqli_fetch_assoc($result);
            $arr[1]=$row["booking_time"];
            echo json_encode($arr);
        }

        function GetShowTime()
        {
            $response = array();
            $province_id=$cinema_id=$date="";
            if($_SERVER['REQUEST_METHOD'] == "POST"){
                $province_id=$_POST["province_id"];
                $cinema_id=$_POST["cinema_id"];
                $date=$_POST["date"];
                $result = $this->model("AdminModel")->getShowTime(
                    $province_id,
                    $cinema_id,
                    $date
                );
                $arr = array();
                $index=0;
                $newItem=[];
                while($row = mysqli_fetch_assoc($result)){
                    $newItem[$index]["show_time_id"] = $row["show_time_id"];
                    $newItem[$index]["room_id"] = $row["room_id"];
                    $newItem[$index]["room_name"] = $row["room_name"];
                    $newItem[$index]["show_time_date"] = $row["show_time_date"];
                    $index++;
                }
                $arr["data"]=$newItem;
                echo json_encode($arr);

            }
        }

        function CheckLogin()
        {
            $response = array();
            $user_name=$password=$password_check="";
            if($_SERVER['REQUEST_METHOD'] == "POST"){
//                 User Name
                if(isset($_POST["user_name"]) && !empty($_POST["user_name"]) && !is_null($_POST["user_name"])){
                    $user_name=$_POST["user_name"];
                }else{
                    $response = array(
                        "status" => "error",
                        "error" => true,
                        "message" => "Please Input User Name"
                    );
                    echo json_encode($response);
                    return;
                }
//                 Password
                if(isset($_POST["password"]) && !empty($_POST["password"]) && !is_null($_POST["password"])){
                    $password=$_POST["password"];
                    $role_check = $this->model("AdminModel")->checkUser($user_name,$password);
                    if($role_check==="1"){
                        $response = array(
                            "status" => "success",
                            "error" => false,
                            "message" => "Login Success",
                            "role" => "admin"
                        );
                        echo json_encode($response);
                        return;
                    }
                    if($role_check==="2"){
                        $response = array(
                            "status" => "success",
                            "error" => false,
                            "message" => "Login Success",
                            "role" => "super-admin"
                        );
                        echo json_encode($response);
                        return;
                    }
                    $response = array(
                        "status" => "error",
                        "error" => true,
                        "message" => "Wrong user or password"
                    );
                    echo json_encode($response);
                    return;
                }else{
                    $response = array(
                        "status" => "error",
                        "error" => true,
                        "message" => "Please Input password"
                    );
                    echo json_encode($response);
                    return;
                }
            }
        }

        function GetAllAccount()
        {
            $response = array();
            if($_SERVER['REQUEST_METHOD'] == "POST"){
                $result = $this->model("AdminModel")->getAllAccount();
                $arr = array();
                $index=0;
                $newItem=[];
                while($row = mysqli_fetch_assoc($result)){
                    $newItem[$index]["admin_id"] = $row["admin_id"];
                    $newItem[$index]["admin_email"] = $row["admin_email"];
                    $newItem[$index]["admin_role"] = $row["admin_role"];
                    $newItem[$index]["admin_name"] = $row["admin_name"];
                    $newItem[$index]["admin_number"] = $row["admin_number"];
                    $index++;
                }
                $arr["data"]=$newItem;
                echo json_encode($arr);
            }
        }

        function CreateNewAccount()
        {
            $response = array();
            $admin_user_name=$admin_password=$admin_role=$admin_name=$admin_number_phone="";
            if($_SERVER['REQUEST_METHOD'] == "POST"){
                $admin_user_name=$_POST["admin_user_name"];
                $admin_password=$_POST["admin_password"];
                $admin_role=$_POST["admin_role"];
                $admin_name=$_POST["admin_name"];
                $admin_number_phone=$_POST["admin_number_phone"];
                $qr="
                    select count(*) as count from admin where admin_email='".$admin_user_name."';
                ";
                $result= mysqli_query($this->DB->conn,$qr);
                $check_user=mysqli_fetch_assoc($result)["count"];

                if($check_user==="0"){
                    $check= $this->model("AdminModel")->createNewAccount(
                        $admin_user_name,
                        $admin_password,
                        $admin_role,
                        $admin_name,
                        $admin_number_phone
                    );
                    if($check=true){
                        $response = array(
                            "status" => "success",
                            "error" => false,
                            "message" => "Create Account Successful"
                        );
                        echo json_encode($response);
                        return;
                    }else{
                        $response = array(
                            "status" => "error",
                            "error" => true,
                            "message" => "Unknown error"
                        );
                        echo json_encode($response);
                        return;
                    }
                }else{
                    $response = array(
                        "status" => "error",
                        "error" => true,
                        "message" => "This Account name have been created"
                    );
                    echo json_encode($response);
                    return;
                }


            }
        }


        function GetAllTicket()
        {
            $response = array();
            if($_SERVER['REQUEST_METHOD'] == "POST"){
                $dataTicket = $this->model("AdminModel")->getAllTicket();
                $this->api("api-tickets",[
                    "tickets" =>  $dataTicket
                ]);
            }
        }

        function GetAllTicketFromTo()
        {
            $response = array();
            $province_id=$cinema_id=0;
            $time_start=$time_end="";
            if($_SERVER['REQUEST_METHOD'] == "POST"){
                $time_start=$_POST["time_start"];
                $time_end=$_POST["time_end"];
                $province_id=$_POST["province_id"];
                $cinema_id=$_POST["cinema_id"];

                $dataTicket = $this->model("AdminModel")->getAllTicketFromTo(
                    $time_start,
                    $time_end,
                    $province_id,
                    $cinema_id
                );
                $this->api("api-tickets",[
                    "tickets" =>  $dataTicket
                ]);
            }
        }

        function CreateNewProvince()
        {
            $province = "";
            $response = array();
            // echo $_POST["province_name"]=="";
            if($_SERVER['REQUEST_METHOD'] == "POST"){
                if(isset($_POST["province_name"]) ){
                    if(empty($_POST["province_name"]) || is_null($_POST["province_name"])){
                        $response = array(
                            "status" => "error",
                            "error" => true,
                            "message" => "Please Input Province Name"
                        );
                        echo json_encode($response);
                        return;
                    }
                    $province=$_POST["province_name"];
                    $isExist = $this->model("AdminModel")->checkProvinceExist($province);
                    if($isExist>0){
                        $response = array(
                            "status" => "error",
                            "error" => true,
                            "message" => "This Province have been created, Please Enter another Province"
                        );
                    }else{
                        $qr = "insert into province(province_name) value ('".$province."');";
                        if(mysqli_query($this->DB->conn,$qr) == TRUE){
                            $response = array(
                                "status" => "success",
                                "error" => false,
                                "message" => "Done, Province have been created"
                            );
                        }else{
                            $response = array(
                                "status" => "error",
                                "error" => true,
                                "message" => "Error: " . $qr . "<br>" . $this->DB->conn->error
                            );
                        }
                    }
                }else{
                    $response = array(
                        "status" => "error",
                        "error" => true,
                        "message" => "Please Input Province Name"
                    );
                }
                echo json_encode($response);
            }
        }

        function CreateNewCinema()
        {
            $cinema="";
            $province_id="";
            $cinemaErr = $province_idErr = "";
            $response = array();
            // echo $_POST["province_id"];
            // echo $_POST["cinema_name"];
            if($_SERVER['REQUEST_METHOD'] == "POST"){
                if(!isset($_POST["province_id"]) || empty($_POST["province_id"]) || is_null($_POST["province_id"])){
                    $response = array(
                        "status" => "error",
                        "error" => true,
                        "message" => "Please Select Province"
                    );
                    echo json_encode($response);
                    return;
                }else{
                    $province_id=$_POST["province_id"];
                }

                if(!isset($_POST["cinema_name"]) || empty($_POST["cinema_name"]) || is_null($_POST["cinema_name"])){
                    $response = array(
                        "status" => "error",
                        "error" => true,
                        "message" => "Please Input Cinema Name"
                    );
                    echo json_encode($response);
                    return;
                }else{
                    $cinema=$_POST["cinema_name"];

                    $isExist = $this->model("AdminModel")->checkCinemaExist($cinema);
                    if($isExist>0){
                        $response = array(
                            "status" => "error",
                            "error" => true,
                            "message" => "This Cinema have been created, Please Enter another Cinema"
                        );
                    }else{
                        $qr = "insert into cinema(cinema_name, province_id) value ('".$cinema."', '".$province_id."');";
                        if(mysqli_query($this->DB->conn,$qr) == TRUE){
                            $response = array(
                                "status" => "success",
                                "error" => false,
                                "message" => "Done, Cinema have been created"
                            );
                        }else{
                            $response = array(
                                "status" => "error",
                                "error" => true,
                                "message" => "Error: " . $qr . "<br>" . $this->DB->conn->error
                            );
                        }
                    }

                }
                echo json_encode($response);
            }
        }


        function CreateNewRoom()
        {
            $cinema_id = "";
            $province_id= "";
            $room_type= "";
            $seat_number= "";
            $response = array();
            if($_SERVER['REQUEST_METHOD'] == "POST"){

//                province id
                if(!isset($_POST["province_id"]) || empty($_POST["province_id"]) || is_null($_POST["province_id"])){
                    $response = array(
                        "status" => "error",
                        "error" => true,
                        "message" => "Please Select Province"
                    );
                    echo json_encode($response);
                    return;
                }else{
                    $province_id = $_POST["province_id"];
                }

//                cinema id
                if(!isset($_POST["cinema_id"]) || empty($_POST["cinema_id"]) || is_null($_POST["cinema_id"])){
                    $response = array(
                        "status" => "error",
                        "error" => true,
                        "message" => "Please Select Cinema"
                    );
                    echo json_encode($response);
                    return;
                }else{
                    $cinema_id=$_POST["cinema_id"];
                }

//                room type
                if(!isset($_POST["room_type"]) || empty($_POST["room_type"])  || is_null($_POST["room_type"])){
                    $response = array(
                        "status" => "error",
                        "error" => true,
                        "message" => "Please Select Room Type",
                    );
                    echo json_encode($response);
                    return;
                }else{
                    $room_type=$_POST["room_type"];
                }

//                seat number
                if(!isset($_POST["seat_number"]) || empty($_POST["seat_number"]) || is_null($_POST["seat_number"])){
                    $response = array(
                        "status" => "error",
                        "error" => true,
                        "message" => "Please Input Number of Seat",
                    );
                    echo json_encode($response);
                    return;
                }else{
                    $seat_number=$_POST["seat_number"];
                    if(($seat_number<10) || ($seat_number>50)){
                        $response = array(
                            "status" => "error",
                            "error" => true,
                            "message" => "The number of seat is between 10 and 50",
                        );
                        echo json_encode($response);
                        return;
                    }else{
                        $room = $this->model("AdminModel");
                        $check = $room->createNewRoom($cinema_id, $room_type, $seat_number);
                        if($check==1){
                            $response = array(
                                "status" => "success",
                                "error" => false,
                                "message" => "The room have been created",
                            );
                            echo json_encode($response);
                            return;
                        }
                        if($check==3){
                            $response = array(
                                "status" => "error",
                                "error" => true,
                                "message" => "Can not create New Room",
                            );
                            echo json_encode($response);
                            return;
                        }
                        if($check==2){
                            $response = array(
                                "status" => "error",
                                "error" => true,
                                "message" => "Can not create New Seat",
                            );
                            echo json_encode($response);
                            return;
                        }
                    }
                }
                return;
            }
        }

        function CreateNewShowTime()
        {
            $response = array();
            $cinema_id = "";
            $province_id= "";
            $room_id= "";
            $movie_id="";
            $show_time_date="";
            if($_SERVER['REQUEST_METHOD'] == "POST"){
                $movie_id=$_POST["movie_id"];
                $province_id = $_POST["province_id"];
                $cinema_id=$_POST["cinema_id"];
                $show_time_date=$_POST["show_time_date"];
                $room_id=$_POST["room_id"];
                $check_show_time= $this->model("AdminModel")->createNewShowTime(
                    $province_id,
                    $cinema_id,
                    $room_id,
                    $movie_id,
                    $show_time_date
                );
                if($check_show_time===true){
                    $check_seat_status=$this->model("AdminModel")->createSeatStatus(
                       $show_time_date,
                       $room_id
                    );
                    if($check_seat_status===true){
                        $response = array(
                            "status" => "success",
                            "error" => false,
                            "message" => "The Show Time have been Created"
                        );
                    }else{
                        $check_delete_show_time = $this->model("AdminModel")->deleteShowTime(
                            $show_time_date,
                            $room_id
                        );
                        $response = array(
                            "status" => "error",
                            "error" => true,
                            "message" => "The Show Time Cant be Create"
                        );
                    }
                }else{
                    $response = array(
                        "status" => "error",
                        "error" => true,
                        "message" => "The Show Time Cant be Create"
                    );
                }
                echo json_encode($response);
            }
        }




        function CheckNewShowTime()
        {
            $response = array();
            $cinema_id = "";
            $province_id= "";
            $room_id= "";
            $movie_id="";
            $show_time_date="";

            if($_SERVER['REQUEST_METHOD'] == "POST"){

    //          movie id
                    if(!isset($_POST["movie_id"]) || empty($_POST["movie_id"]) || is_null($_POST["movie_id"])){
                        $response = array(
                            "status" => "error",
                            "error" => true,
                            "message" => "Please Select a movie"
                        );
                        echo json_encode($response);
                        return;
                    }else{
                        $movie_id=$_POST["movie_id"];
                    }

    //          province id
                if(!isset($_POST["province_id"]) || empty($_POST["province_id"]) || is_null($_POST["province_id"])){
                    $response = array(
                        "status" => "error",
                        "error" => true,
                        "message" => "Please Select Province"
                    );
                    echo json_encode($response);
                    return;
                }else{
                    $province_id = $_POST["province_id"];
                }

    //          cinema id
                if(!isset($_POST["cinema_id"]) || empty($_POST["cinema_id"]) || is_null($_POST["cinema_id"])){
                    $response = array(
                        "status" => "error",
                        "error" => true,
                        "message" => "Please Select Cinema"
                    );
                    echo json_encode($response);
                    return;
                }else{
                    $cinema_id=$_POST["cinema_id"];
                }

    //          show_time_date
                    if(!isset($_POST["show_time_date"]) ||
                        empty($_POST["show_time_date"]) ||
                        is_null($_POST["show_time_date"])){
                        $response = array(
                            "status" => "error",
                            "error" => true,
                            "message" => "Please Select a Time you want to create a new show time"
                        );
                        echo json_encode($response);
                        return;
                    }else{
                        $show_time_date=$_POST["show_time_date"];
                    }

    //          room id
                if(!isset($_POST["room_id"]) || empty($_POST["room_id"]) || is_null($_POST["room_id"])){
                    $response = array(
                        "status" => "error",
                        "error" => true,
                        "message" => "Please Select a room"
                    );
                    echo json_encode($response);
                    return;
                }else{
                    $room_id=$_POST["room_id"];
                }
            }

            $check = $this->model("AdminModel")->checkNewShowtime(
                $room_id, $movie_id , $show_time_date
            );

            $response = array(
                "status" => "success",
                "error" => false,
                "message" => "The New Show Time have been created",
                "province" => $province_id,
                "cinema" => $cinema_id,
                "room_id" => $room_id,
                "movie_id" => $movie_id,
                "show_time_date" => $show_time_date,
                "Checked" => $check,
            );
            echo json_encode($response);
        }

        function CreateNewActor(){
            $response = array();
            $actor_name=
            $actor_name_banner=
            $location=
            $avatar=
            $banner=
            $date_of_birth=
            $occupation=
            $biography =
            "";
            $allowed = array("jpg" => "image/jpg",
                "jpeg" => "image/jpeg",
                "gif" => "image/gif",
                "png" => "image/png");
            $avatar_tmp_name = '';
            $banner_tmp_name = '';
            $upload_avatar_dir = 'Public/Imgs/avatar_small/';
            $upload_banner_dir = 'Public/Imgs/banner_movie/';
            $upload_avatar_name = '';
            $upload_banner_name = '';
            $server_url = 'http://localhost/Cinema/';

            if($_SERVER['REQUEST_METHOD'] == "POST") {

//                Actor Name
                if (!isset($_POST["actor_name"]) ||
                    empty($_POST["actor_name"]) ||
                    is_null($_POST["actor_name"])
                ){
                    $response = array(
                        "status" => "error",
                        "error" => true,
                        "message" => "Please Input Actor Name"
                    );
                    echo json_encode($response);
                    return;
                } else {
                    $actor_name = $_POST["actor_name"];

                    $isExist = $this->model("AdminModel")->checkActorExist($actor_name);
                    if($isExist>0){
                        $response = array(
                            "status" => "error",
                            "error" => true,
                            "message" => "This Actor have been created, Please Enter another Actor"
                        );
                        echo json_encode($response);
                        return;
                    }
                }

//                Actor Name Banner
                if (!isset($_POST["actor_name_banner"]) ||
                    empty($_POST["actor_name_banner"]) ||
                    is_null($_POST["actor_name_banner"])
                ){
                    $response = array(
                        "status" => "error",
                        "error" => true,
                        "message" => "Please Input Actor Name Banner"
                    );
                    echo json_encode($response);
                    return;
                } else {
                    $actor_name_banner = $_POST["actor_name_banner"];
                }

//                Location
                if (!isset($_POST["location"]) ||
                    empty($_POST["location"]) ||
                    is_null($_POST["location"])
                ){
                    $response = array(
                        "status" => "error",
                        "error" => true,
                        "message" => "Please Input location of actor"
                    );
                    echo json_encode($response);
                    return;
                } else {
                    $location = $_POST["location"];
                }


//                Avatar
                if(isset($_FILES['avatar']))
                {

                    // Lấy thông tin file bao gồm tên file, loại file, kích cỡ file
                    $avatar_name = $_FILES["avatar"]["name"];
                    $avatar_tmp_name = $_FILES["avatar"]["tmp_name"];

                    // Kiểm tra định dạng file .jpg, png,...
                    $ext_avatar = pathinfo($avatar_name, PATHINFO_EXTENSION);

                    // Nếu không đúng định dạng file thì báo lỗi
                    if(!array_key_exists($ext_avatar, $allowed)) {
                        $response = array(
                            "status" => "error",
                            "error" => true,
                            "message" => "Error Image, Please Choose another Avatar Image"
                        );
                        echo json_encode($response);
                        return;
                    }
                    else
                    {
                        $random_avatar_name = rand(1000,1000000)."-".$avatar_name;
                        $upload_avatar_name = $upload_avatar_dir.strtolower($random_avatar_name);
                        $upload_avatar_name = preg_replace('/\s+/', '-', $upload_avatar_name);
                    }
                }else{
                    $response = array(
                        "status" => "error",
                        "error" => true,
                        "message" => "No Avatar Image was sent!"
                    );
                    echo json_encode($response);
                    return;
                }



//                Banner
                if(isset($_FILES['banner']))
                {
                    // Lấy thông tin file bao gồm tên file, loại file, kích cỡ file
                    $banner_name = $_FILES["banner"]["name"];
                    $banner_tmp_name = $_FILES["banner"]["tmp_name"];
//                    $error = $_FILES["banner"]["error"];

                    // Kiểm tra định dạng file .jpg, png,...
                    $ext_banner = pathinfo($banner_name, PATHINFO_EXTENSION);

                    // Nếu không đúng định dạng file thì báo lỗi
                    if(!array_key_exists($ext_banner, $allowed)) {
                        $response = array(
                            "status" => "error",
                            "error" => true,
                            "message" => "Error Image, Please Choose another Banner Image"
                        );
                        echo json_encode($response);
                        return;
                    }
                    else
                    {
                        $random_banner_name = rand(1000,1000000)."-".$banner_name;
                        $upload_banner_name = $upload_banner_dir.strtolower($random_banner_name);
                        $upload_banner_name = preg_replace('/\s+/', '-', $upload_banner_name);
                        // echo $upload_name;
                    }
                }else{
                    $response = array(
                        "status" => "error",
                        "error" => true,
                        "message" => "No Banner Image was sent!"
                    );
                    echo json_encode($response);
                    return;
                }

//                  Date of birth
                if (!isset($_POST["date_of_birth"]) ||
                    empty($_POST["date_of_birth"]) ||
                    is_null($_POST["date_of_birth"])
                ){
                    $response = array(
                        "status" => "error",
                        "error" => true,
                        "message" => "Please Input choose a date of birth"
                    );
                    echo json_encode($response);
                    return;
                } else {
                    $date_of_birth = $_POST["date_of_birth"];
                }

//                  Occupation
                if (!isset($_POST["occupation"]) ||
                    empty($_POST["occupation"]) ||
                    is_null($_POST["occupation"])
                ){
                    $response = array(
                        "status" => "error",
                        "error" => true,
                        "message" => "Please Input Occupation of actor"
                    );
                    echo json_encode($response);
                    return;
                } else {
                    $occupation = $_POST["occupation"];
                }

//                  Biography
                if (!isset($_POST["biography"]) ||
                    empty($_POST["biography"]) ||
                    is_null($_POST["biography"])
                ){
                    $response = array(
                        "status" => "error",
                        "error" => true,
                        "message" => "Please Input Biography of actor"
                    );
                    echo json_encode($response);
                    return;
                } else {
                    $biography = $_POST["biography"];
                }

                if(!move_uploaded_file($avatar_tmp_name , $upload_avatar_name))
                {
                    $response = array(
                        "status" => "error",
                        "error" => true,
                        "message" => "Error uploading the Avatar Image!"
                    );
                    echo json_encode($response);
                    return;
                }

                if(!move_uploaded_file($banner_tmp_name , $upload_banner_name))
                {
                    $response = array(
                        "status" => "error",
                        "error" => true,
                        "message" => "Error uploading the Banner Image!"
                    );
                    echo json_encode($response);
                    return;
                }

                $admin= $this->model("AdminModel");
                $checkActor = $admin->createNewActor(
                    $actor_name,
                    $actor_name_banner,
                    $server_url."/".$upload_avatar_name,
                    $server_url."/".$upload_banner_name,
                    $location,
                    $date_of_birth,
                    $occupation,
                    $biography
                );
                if(!$checkActor){
                    $response = array(
                        "status" => "error",
                        "error" => true,
                        "message" => "Cant Add New Actor",
                    );
                    echo json_encode($response);
                    return;
                };

                $response = array(
                    "status" => "success",
                    "error" => false,
                    "message" => "File uploaded successfully",
                    "avatar_url" => $server_url."/".$upload_avatar_name,
                    "banner_url" => $server_url."/".$upload_banner_name,
                );
                echo json_encode($response);

            }
        }

        function CreateNewMovie(){
            $response = array();
            $movie_name=
            $movie_name_banner=
            $description=
            $avatar=
            $banner=
            $release=
            $language=
            $main_type=
            $country=
            $duration=
            "";
            $allowed = array("jpg" => "image/jpg",
                "jpeg" => "image/jpeg",
                "gif" => "image/gif",
                "png" => "image/png");
            $avatar_tmp_name = '';
            $banner_tmp_name = '';
            $upload_avatar_dir = 'Public/Imgs/firm_small/';
            $upload_banner_dir = 'Public/Imgs/banner_actor/';
            $upload_avatar_name = '';
            $upload_banner_name = '';
            $server_url = 'http://localhost/Cinema/';

            if($_SERVER['REQUEST_METHOD'] == "POST") {

//                Movie Name
                if (!isset($_POST["movie_name"]) ||
                    empty($_POST["movie_name"]) ||
                    is_null($_POST["movie_name"])
                ){
                    $response = array(
                        "status" => "error",
                        "error" => true,
                        "message" => "Please Input Movie Name"
                    );
                    echo json_encode($response);
                    return;
                } else {
                    $movie_name = $_POST["movie_name"];

                    $isExist = $this->model("AdminModel")->checkMovieExist($movie_name);
                    if($isExist>0){
                        $response = array(
                            "status" => "error",
                            "error" => true,
                            "message" => "This Movie have been created, Please Enter another Movie"
                        );
                        echo json_encode($response);
                        return;
                    }
                }

//                Movie name banner
                if (!isset($_POST["movie_name_banner"]) ||
                    empty($_POST["movie_name_banner"]) ||
                    is_null($_POST["movie_name_banner"])
                ){
                    $response = array(
                        "status" => "error",
                        "error" => true,
                        "message" => "Please Input Movie Name Banner"
                    );
                    echo json_encode($response);
                    return;
                } else {
                    $movie_name_banner = $_POST["movie_name_banner"];
                }

//                Language
                if (!isset($_POST["language"]) ||
                    empty($_POST["language"]) ||
                    is_null($_POST["language"])
                ){
                    $response = array(
                        "status" => "error",
                        "error" => true,
                        "message" => "Please Input Language"
                    );
                    echo json_encode($response);
                    return;
                } else {
                    $language = $_POST["language"];
                }

//                Avatar
                if(isset($_FILES['avatar']))
                {

                    // Lấy thông tin file bao gồm tên file, loại file, kích cỡ file
                    $avatar_name = $_FILES["avatar"]["name"];
                    $avatar_tmp_name = $_FILES["avatar"]["tmp_name"];

                    // Kiểm tra định dạng file .jpg, png,...
                    $ext_avatar = pathinfo($avatar_name, PATHINFO_EXTENSION);

                    // Nếu không đúng định dạng file thì báo lỗi
                    if(!array_key_exists($ext_avatar, $allowed)) {
                        $response = array(
                            "status" => "error",
                            "error" => true,
                            "message" => "Error Image, Please Choose another Avatar Image"
                        );
                        echo json_encode($response);
                        return;
                    }
                    else
                    {
                        $random_avatar_name = rand(1000,1000000)."-".$avatar_name;
                        $upload_avatar_name = $upload_avatar_dir.strtolower($random_avatar_name);
                        $upload_avatar_name = preg_replace('/\s+/', '-', $upload_avatar_name);
                        // echo $upload_name;
                    }
                }else{
                    $response = array(
                        "status" => "error",
                        "error" => true,
                        "message" => "No Avatar Image was sent!"
                    );
                    echo json_encode($response);
                    return;
                }


//                Banner
                if(isset($_FILES['banner']))
                {
                    // Lấy thông tin file bao gồm tên file, loại file, kích cỡ file
                    $banner_name = $_FILES["banner"]["name"];
                    $banner_tmp_name = $_FILES["banner"]["tmp_name"];
//                    $error = $_FILES["banner"]["error"];

                    // Kiểm tra định dạng file .jpg, png,...
                    $ext_banner = pathinfo($banner_name, PATHINFO_EXTENSION);

                    // Nếu không đúng định dạng file thì báo lỗi
                    if(!array_key_exists($ext_banner, $allowed)) {
                        $response = array(
                            "status" => "error",
                            "error" => true,
                            "message" => "Error Image, Please Choose another Banner Image"
                        );
                        echo json_encode($response);
                        return;
                    }
                    else
                    {
                        $random_banner_name = rand(1000,1000000)."-".$banner_name;
                        $upload_banner_name = $upload_banner_dir.strtolower($random_banner_name);
                        $upload_banner_name = preg_replace('/\s+/', '-', $upload_banner_name);
                        // echo $upload_name;
                    }
                }else{
                    $response = array(
                        "status" => "error",
                        "error" => true,
                        "message" => "No Banner Image was sent!"
                    );
                    echo json_encode($response);
                    return;
                }

//                Release Date
                if (!isset($_POST["release"]) ||
                    empty($_POST["release"]) ||
                    is_null($_POST["release"])
                ){
                    $response = array(
                        "status" => "error",
                        "error" => true,
                        "message" => "Please Choose Release Date"
                    );
                    echo json_encode($response);
                    return;
                } else {
                    $release = $_POST["release"];
                }

//                Duration
                if (!isset($_POST["duration"]) ||
                    empty($_POST["duration"]) ||
                    is_null($_POST["duration"])
                ){
                    $response = array(
                        "status" => "error",
                        "error" => true,
                        "message" => "Please Input Duration"
                    );
                    echo json_encode($response);
                    return;
                } else {
                    $duration = $_POST["duration"];
                }

//                Main type
                if (!isset($_POST["main_type"]) ||
                    empty($_POST["main_type"]) ||
                    is_null($_POST["main_type"])
                ){
                    $response = array(
                        "status" => "error",
                        "error" => true,
                        "message" => "Please Input Main type"
                    );
                    echo json_encode($response);
                    return;
                } else {
                    $main_type = $_POST["main_type"];
                }

//                Country
                if (!isset($_POST["country"]) ||
                    empty($_POST["country"]) ||
                    is_null($_POST["country"])
                ){
                    $response = array(
                        "status" => "error",
                        "error" => true,
                        "message" => "Please Input Country"
                    );
                    echo json_encode($response);
                    return;
                } else {
                    $country = $_POST["country"];
                }

//                Description
                if (!isset($_POST["description"]) ||
                    empty($_POST["description"]) ||
                    is_null($_POST["description"])
                ){
                    $response = array(
                        "status" => "error",
                        "error" => true,
                        "message" => "Please Input Description"
                    );
                    echo json_encode($response);
                    return;
                } else {
                    $description = $_POST["description"];
                }

            }

            if(!move_uploaded_file($avatar_tmp_name , $upload_avatar_name))
            {
                $response = array(
                    "status" => "error",
                    "error" => true,
                    "message" => "Error uploading the Avatar Image!"
                );
                echo json_encode($response);
                return;
            }

            if(!move_uploaded_file($banner_tmp_name , $upload_banner_name))
            {
                $response = array(
                    "status" => "error",
                    "error" => true,
                    "message" => "Error uploading the Banner Image!"
                );
                echo json_encode($response);
                return;
            }

            $admin= $this->model("AdminModel");
            $check = $admin->createNewMovie(
                $movie_name,
                $movie_name_banner,
                $description,
                $server_url."/".$upload_avatar_name,
                $server_url."/".$upload_banner_name,
                $release,
                $language,
                $main_type,
                $country,
                $duration
            );
            if(!$check){
                $response = array(
                    "status" => "error",
                    "error" => true,
                    "message" => "Cant Add New Movie",
                );
                echo json_encode($response);
                return;
            };

            $response = array(
                "status" => "success",
                "error" => false,
                "message" => "File uploaded successfully",
                "avatar_url" => $server_url."/".$upload_avatar_name,
                "banner_url" => $server_url."/".$upload_banner_name,
            );
            echo json_encode($response);
        }
    }

