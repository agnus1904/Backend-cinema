<?php
    class LoginModel extends DB{
        public function getLogin($user)
        {
            $qr = "select * from customer where customer_email='".$user."'";
            return mysqli_query($this->conn,$qr);
        }
        public function getId($id){
            $qr = "select customer_id from customer where customer_id=".$id." limit 1;";
            $result = mysqli_query($this->conn,$qr);
            if($result){
                while($row = mysqli_fetch_assoc($result)){
                    return $row["customer_id"];
               }
            }
            return 0;
        }
    }
?>