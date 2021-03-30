<?php
    class CustomerModel extends DB{

        public function checkCusomerEmail($email){
            $qr="select COUNT(*)
                from customer
                where customer_email='".$email."';
            ";
            $result1= mysqli_query($this->conn,$qr);
            $count=mysqli_fetch_assoc($result1)["COUNT(*)"];
            return $count;
        }

        public function createNewCustomer(
            $customerEmail,
            $customerPassword,
            $customerFullName,
            $customerNumberPhone
        ){
            $arr=array();
            $qr="
                insert into customer
                (
                    customer_email,
                    customer_password,
                    customer_name,
                    customer_number_phone
                ) value
                (
                    '".$customerEmail."',
                    '".$customerPassword."',
                    '".$customerFullName."',
                    '".$customerNumberPhone."'
                );
            ";
            if(mysqli_query($this->conn,$qr)){
                $arr["status"]= true;
                $arr["error"]= null;
                return $arr;
            }else{
                $arr["status"]="Cant not create";
                $arr["error"]= mysqli_error($this->conn);
                return $arr;
            }
        }

        public function getMovieBanner()
        {
            $qr = "SELECT movie_id,movie_name_banner,banner_url  FROM movie WHERE movie_id=20";
            return mysqli_query($this->conn,$qr);
        }
    }
?>