<?php
    class User {
        private $db;

        public function __construct($db) {
            $this->db = $db;
        }

        public function login($email, $password) {
            try {
                
                $loginStmt = $this->db->prepare("SELECT user_id, user_fname, user_email, user_pass  
                                                    FROM user WHERE user_email= ? 
                                                    AND user_pass=?");
                
                $loginStmt->bind_param('ss', $email, $password);
                $loginStmt->execute();

                $loginResult = $loginStmt->get_result();
                $rowCount = mysqli_num_rows($loginResult);  

                if ($rowCount == 1) {
                    $userDetail = $loginResult->fetch_assoc();

                    $_SESSION['loggedin'] = $userDetail['user_id'];
                    $_SESSION['loggedin_name'] = $userDetail['user_fname'];
                }

            } catch (PDOException $e) {
                echo $e;
            }
        }
        
    }
?>