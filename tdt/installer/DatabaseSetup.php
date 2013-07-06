<?php 

namespace tdt\installer;

/**
 * Sets up a default database for the Datatank.
 * It uses MySQL; creates a db named 'datatank'
 * Creates a db user named 'datatank' with password 'datatank'
 * Gives the datatank user full rights on the datatank db.
 * 
 * For this to work properly, there should be a temp.json file
 * containing the root MySQL password.
 *
 * @author Benjamin Mestdagh
 * @copyright 2013 by 0KFN Belgium
 */
class DatabaseSetup 
{
    public function getResult()
    {
        $tempconfig = json_decode(file_get_contents('temp.json'));
        $dbconfig = json_decode(file_get_contents('../app/config/db.json'));
        
        $dsn = 'mysql:host=127.0.0.1';
        $user = 'root';
        $password = $tempconfig->rootpassword;

        try 
        {
            $dbh = new \PDO($dsn, $user, $password);
            $dbh->query("create database ".$dbconfig->name);
            $dbh->query("create user '".$dbconfig->user."'@'localhost' identified by '".$dbconfig->password."'");
            $dbh->query("grant all on ".$dbconfig->name.".* to '".$dbconfig->user."'@'localhost'");
            $result = "OK";
        } 
        catch (\PDOException $e) 
        {
            $result = 'Connection failed: ' . $e->getMessage();
        }
    
        return $result;
    }
}