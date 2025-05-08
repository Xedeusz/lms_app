<?php

class database {

    function opencon(): PDO {
        return new PDO(
            'mysql:host=localhost;dbname=lms_app',
            'root',
            ''
        );
    }

   
    function signUpUser($firstname, $lastname, $email, $birthday, $sex, $phone, $username, $password, $profile_picture_path) {
        $con = $this->opencon();
        try { 
            $con->beginTransaction();
            
           
            $stmt = $con->prepare("INSERT INTO Users(
                user_FN, user_LN, user_birthday, user_sex, user_email, user_phone, user_username, user_password) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$firstname, $lastname, $birthday, $sex, $email, $phone, $username, $password]);

            $userID = $con->lastInsertId();  
           
            $stmt = $con->prepare("INSERT INTO users_pictures(user_id, user_pic_url) VALUES (?, ?)");
            $stmt->execute([$userID, $profile_picture_path]);

            $con->commit();  
            return $userID;
        } catch (PDOException $e) {
            $con->rollBack();  
            return false;
        }
    }

  
    function insertAddress($UserID, $street, $barangay, $city, $province) {
        $con = $this->opencon();
        try { 
            $con->beginTransaction();
            
      
            $stmt = $con->prepare("INSERT INTO Address(
                ba_street, ba_barangay, ba_city, ba_province) 
                VALUES (?, ?, ?, ?)");
            $stmt->execute([$street, $barangay, $city, $province]);

            $addressId = $con->lastInsertId();  

            
            $stmt = $con->prepare("INSERT INTO user_Address(user_id, address_id) VALUES (?, ?)");
            $stmt->execute([$UserID, $addressId]);

            $con->commit();  
            return true;
        } catch (PDOException $e) {
            $con->rollBack();  
            return false;
        }
    }
}
?>
