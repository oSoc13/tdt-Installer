<?php

namespace tdt\installer;

/**
 * Copies the example config files and creates the database.
 *
 * @author Benjamin Mestdagh
 * @copyright 2013 by 0KFN Belgium
 */
class SettingsCommitter
{
    private $configPath = "../app/config/";
    private $publicPath = "../public/";

    public function commit($session)
    {
        $this->copyFiles();
        
        // We need to mess with the database if the user chose the default settings,
        // or if the user wanted to create a new user or new database.
        // We also cannot make a connection if the root password is not set.
        $databasecreationNeeded = (($session->get('dbinstalldefault')) ||
            $session->get('dbnewuser') || $session->get('dbnewdb')) &&
            $session->get('dbrootpassword') !== NULL;
        
        if($databasecreationNeeded) {
            $this->createDatabase($session);
        }
    }
    
    private function copyFiles()
    {
        $oldCoresFile = $this->configPath."cores.example.json";
        $newCoresFile = $this->configPath."cores.json";
        copy($oldCoresFile, $newCoresFile);
        
        $oldAuthFile = $this->configPath."auth.example.json";
        $newAuthFile = $this->configPath."auth.json";
        copy($oldAuthFile, $newAuthFile);
        
        copy($this->publicPath."index.example.php", $this->publicPath."index.php");
    }
    
    private function createDatabase($session)
    {
        $dbconfig = json_decode(file_get_contents($this->configPath.'db.json'));
        
        $host = $dbconfig->host;
        $user = $dbconfig->user;
        $name = $dbconfig->name;
        $password = $dbconfig->password;
        
        $dsn = "mysql:host={$host}";

        try
        {
            $dbh = new \PDO($dsn, 'root', $session->get('dbrootpassword'));
            
            if($session->get('dbinstalldefault') || $session->get('dbnewdb')) {
                $dbh->query("create database ". $name);
            }
            
            if($session->get('dbinstalldefault') || $session->get('dbnewuser')) {
                $dbh->query("create user '".$user."'@'localhost' identified by '".$password."'");
            }
            
            $dbh->query("grant all on ".$name.".* to '".$user."'@'localhost'");
        }
        catch (\PDOException $e)
        {
            var_dump($session->get('dbrootpassword'));
            var_dump($e);
        }
    }
}
