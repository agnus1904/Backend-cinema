<?php

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
        function CreateNewShowTime()
        {
            echo "showtime";
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
                echo json_encode($response);
            }
        }

        function CreateNewRoom()
        {
            $cinema_id = "";
            $province_id= "";
            $room_type= "";
            $seat_number= "";

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
                        $room->createNewRoom($cinema_id, $room_type, $seat_number);
                        $response = array(
                            "status" => "success",
                            "error" => false,
                            "message" => "The room have been created",
                        );
                        echo json_encode($response);
                        return;
                    }
                }
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
            $upload_banner_dir = 'Public/Imgs/banner_movie/';
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

//
//            if(!move_uploaded_file($avatar_tmp_name , $upload_avatar_name))
//            {
//                $response = array(
//                    "status" => "error",
//                    "error" => true,
//                    "message" => "Error uploading the Avatar Image!"
//                );
//                echo json_encode($response);
//                return;
//            }
//
//            if(!move_uploaded_file($banner_tmp_name , $upload_banner_name))
//            {
//                $response = array(
//                    "status" => "error",
//                    "error" => true,
//                    "message" => "Error uploading the Banner Image!"
//                );
//                echo json_encode($response);
//                return;
//            }

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

