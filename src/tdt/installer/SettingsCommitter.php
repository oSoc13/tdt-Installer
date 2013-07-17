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
        $copyResult = $this->copyFiles();
        
        // We need to mess with the database if the user chose the default settings,
        // or if the user wanted to create a new user or new database.
        $databasecreationNeeded = ($session->get('dbinstalldefault')) ||
            $session->get('dbnewuser') || $session->get('dbnewdb');
        
        if($databasecreationNeeded) {
            $dbResult = $this->createDatabase($session);
        }
        
        /*$result = $copyResult === true ? '' : $copyResult;
        $result .= $dbResult === true ? '' : $dbResult;*/
        
        /*if($result === '') {
            return true;
        } else {
            return $result;
        }*/
        
        return $copyResult & $dbResult;
    }
    
    private function copyFiles()
    {
        $oldCoresFile = $this->configPath."cores.example.json";
        $newCoresFile = $this->configPath."cores.json";
        $result = copy($oldCoresFile, $newCoresFile);
        
        $oldAuthFile = $this->configPath."auth.example.json";
        $newAuthFile = $this->configPath."auth.json";
        $result = $result & copy($oldAuthFile, $newAuthFile);
        
        $result = $result & copy($this->publicPath."index.example.php", $this->publicPath."index.php");
        
        if($result === false) $result = 'An error occured while copying DataTank configuration files!';
        
        return $result;
    }
    
    private function createDatabase($session)
    {
        $dbconfig = json_decode(file_get_contents($this->configPath.'db.json'));
        
        $host = $dbconfig->host;
        $user = $dbconfig->user;
        $name = str_replace('`', '\'', $dbconfig->name);
        $password = $dbconfig->password;
        
        $dsn = "mysql:host={$host}";

        try
        {
            $dbh = new \PDO($dsn, 'root', $session->get('dbrootpassword'));
            
            if($session->get('dbinstalldefault') || $session->get('dbnewdb')) {
                $dbh->exec("create database `{$name}`");
            }
            
            if($session->get('dbinstalldefault') || $session->get('dbnewuser')) {
                $stmt = $dbh->prepare("create user ?@? identified by ?");
                $stmt->execute(array($user, $host, $password));
            }
            
            $stmt = $dbh->prepare("grant all on `{$name}`.* to ?@?");
            $stmt->execute(array($user, $host));
        }
        catch (\PDOException $e)
        {
            return 'An error occured while performing database tasks!';
        }
        
        return true;
    }
}
