<?php

class User
{
	protected $con;



    function __construct() {



        $this->con = new Connection();

    }
	public function register($uname,$umail,$upass,$phoneNumber)
	{
		
		try
		{
			$new_password = MD5($upass);
			
			$com_code = md5(uniqid(rand()));
			
			$user_role = "customer";
			
			$query = "INSERT INTO bb_user_login(user_name,user_email,password,phone,com_code,user_role) VALUES(:uname, :umail, :upass, :phone, :com_code, :user_role)";
			
			$bindParams = array("uname" => $uname,"umail"=> $umail, "upass" => $new_password, "phone" => $phoneNumber, "com_code" => $com_code, "user_role" => $user_role);
												  		
			$id = $this->con->insertQuery($query, $bindParams);	
			
			if($id){
				$to = $email;
				
				$subject = "Confirmation from Burpbig to $username";
				
				$header = "Burpbig: Confirmation from TutsforWeb";
				
				$message = "Please click the link below to verify and activate your account. rn";
				
				$message .= "http://www.burpbig.com/new/confirm.php?passkey=$com_code";

				$sentmail = mail($to,$subject,$message,$header);
			}
			
			return $id;	
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}				
	}
	
	/* AK
	 * check if email address exist or not
	 */
	public function checkEmailExist($email){
		
		try
		{
		
			$query = "SELECT user_name,user_email FROM bb_user_login WHERE user_name=:uname OR user_email=:umail";
				
			$qh = $this->con->getQueryHandler($query, array("uname"=>$email,"umail"=>$email));
				
			$row=$qh->fetch(PDO::FETCH_ASSOC);
			
			if($row['user_name']==$email) {
				return false;
			}
			else if($row['user_email']==$email) {
				return false;
			}
			
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
		return true;
	}
	
	public function login($uname,$umail,$upass)
	{
		try
		{
			$query = "SELECT * FROM bb_user_login WHERE user_name=:uname OR user_email=:umail LIMIT 1";
			
			$qh = $this->con->getQueryHandler($query, array("uname"=>$uname,"umail"=>$umail));
			
			$userRow=$qh->fetch(PDO::FETCH_ASSOC);
			
			if($qh->rowCount() > 0)
			{
				if($userRow['password']==MD5($upass))
				{
					$_SESSION['user_session'] = $userRow['user_id'];
					return true;
				}
				else
				{
					return false;
				}
			}
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	
	// AK FOrgot Password
	public function forgotPassword($email)
	{
	
		try
		{
			$generatedPassword = $this->generatePassword(8);
			$new_password = MD5($generatedPassword);
			echo $generatedPassword;
	
			$query = "Update bb_user_login SET password=:upass where user_email=:uemail";
	
			$bindParams = array("upass" => $new_password, "uemail" => $email);
			
			$to = $email;
			
			$subject = "Temporary Password from Burpbig";
			
			$header = "Burpbig: Temporary Password";
			
			$message = "Please find below temporary password to login your account. rn";
			
			$message .= "Email: ".$email ."rn";
			
			$message .= "Password: ".$generatedPassword;
			
			$sentmail = mail($to,$subject,$message,$header);
	
			$this->con->insertQuery($query, $bindParams);

		}catch(PDOException $e)
			{
				echo $e->getMessage();
			}
		
	}
	
	function generatePassword($_len) {
	
		$_alphaSmall = 'abcdefghijklmnopqrstuvwxyz';            // small letters
		$_alphaCaps  = strtoupper($_alphaSmall);                // CAPITAL LETTERS
		$_numerics   = '1234567890';                            // numerics
		$_specialChars = '`~!@#$%^&*()-_=+]}[{;:,<.>/?\'"\|';   // Special Characters
	
		$_container = $_alphaSmall.$_alphaCaps.$_numerics.$_specialChars;   // Contains all characters
		$password = '';         // will contain the desired pass
	
		for($i = 0; $i < $_len; $i++) {                                 // Loop till the length mentioned
			$_rand = rand(0, strlen($_container) - 1);                  // Get Randomized Length
			$password .= substr($_container, $_rand, 1);                // returns part of the string [ high tensile strength ;) ]
		}
	
		return $password;       // Returns the generated Pass
	}
	
	public function is_loggedin()
	{
		if(isset($_SESSION['user_session']))
		{
			return true;
		}
	}
	
	public function redirect($url)
	{
		header("Location: $url");
	}
	public function verify_user($passkey)
	{
		try{
		$query = "UPDATE `bb_user_login` SET `com_code` = :value WHERE `com_code` = :passkey";
		
		$bindParams = array("value" => "verified", "passkey" => $passkey);
		
		$id = $this->con->insertQuery($query, $bindParams);	
		
		return $id;
		}
		catch(PDOException $e){
			echo $e->getMessage();
		}
	}
	public function logout()
	{
		session_destroy();
		unset($_SESSION['user_session']);
		return true;
	}
}
?>