<?php
/**
 * Description of Connection
 *
 * @author KDS
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);
class Connection {
    //put your code here
    
    protected $con;
    protected $qh;
    public function __construct()
    {
        try {
            $db_host = 'localhost';  //  hostname
            $db_name = 'burpblqx_bigburp';  //  databasename
            $db_user = 'burpblqx_burpbig';  //  username
            $user_pw = 'Kunal@@@138831138';  //  password

            $this->con = new PDO('mysql:host='.$db_host.'; dbname='.$db_name, $db_user, $user_pw);  
            $this->con->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            //$this->con->exec("SET CHARACTER SET utf8mb4");  //  return all sql requests as UTF-8              
        }
        catch (PDOException $err) {  
            echo "Connection fails: The possible reason is: ".
            $err->getMessage() . "<br/>";
            file_put_contents('PDOErrors.txt',$err, FILE_APPEND);  // write some details to an error-log outside public_html  
            die();  //  terminate connection
        }
    }

    public function dbh()
    {
        return $this->con;
    }
    
    public function getQueryHandler($query,$bind_params = array()) 
    {
        $this->qh = $this->con->prepare($query);
        if(empty($bind_params)) {
            $this->qh->execute();
        }
        else {
            $this->qh->execute($bind_params);
        }
        return $this->qh;
    }
    
    public function insertQuery($query, $bind_params = array())
    {
        $this->qh = $this->con->prepare($query);
        if(empty($bind_params)) {
            $this->qh->execute();
            return $this->con->lastInsertId();
        }
        else {
            $this->qh->execute($bind_params);
            return $this->con->lastInsertId();
        }
    }
    
    
}