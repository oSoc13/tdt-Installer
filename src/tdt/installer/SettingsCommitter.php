<?php

namespace tdt\installer;

/**
 * Copies the example config files and creates the database.
 *
 * @author Benjamin Mestdagh
 * @copyright 2013 by 0KFN Belgium
 */
class SettingsCommitter {

    private $configPath = "../app/config/";
    private $publicPath = "../public/";

    public function commit($session) {
        $copyResult = $this->copyFiles();
        
        // We need to mess with the database if the user chose the default settings,
        // or if the user wanted to create a new user or new database.
        $databasecreationNeeded = ($session->get('dbinstalldefault')) ||
            $session->get('dbnewuser') || $session->get('dbnewdb');
        
        if($databasecreationNeeded) {
            $dbResult = $this->createDatabase($session);
        } else {
            $dbResult = true;
        }
        
        return $copyResult & $dbResult;
    }
    
    private function copyFiles() {
        $oldCoresFile = $this->configPath."cores.example.json";
        $newCoresFile = $this->configPath."cores.json";
        $coreresult = copy($oldCoresFile, $newCoresFile);
        \tdt\installer\LogWriter::write('Copying cores.json example file: ' . ($coreresult ? 'OK' : 'Error'));
        
        $oldAuthFile = $this->configPath."auth.example.json";
        $newAuthFile = $this->configPath."auth.json";
        $authresult = copy($oldAuthFile, $newAuthFile);
        \tdt\installer\LogWriter::write('Copying auth.json example file: ' . ($authresult ? 'OK' : 'Error'));
        
        $indexresult = copy($this->publicPath."index.example.php", $this->publicPath."index.php");
        
        //$logmessage = 'Copying example files: ' . ($result ? 'OK' : 'Error');
        \tdt\installer\LogWriter::write('Copying index.php example file: ' . ($indexresult ? 'OK' : 'Error'));
        
        return $coreresult & $authresult & $indexresult;
    }
    
    private function createDatabase($session) {
        $dbconfig = json_decode(file_get_contents($this->configPath.'db.json'));
        
        $host = $dbconfig->host;
        $user = $dbconfig->user;
        $name = str_replace('`', '\'', $dbconfig->name);
        $password = $dbconfig->password;
        
        $dsn = "mysql:host={$host}";

        try {
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
            
            \tdt\installer\LogWriter::write('Database actions successful.');
        } catch (\PDOException $e) {
            $logmessage = 'Database error: ' . $e->getMessage();
            \tdt\installer\LogWriter::write($logmessage);
            return false;
        }
        
        return true;
    }
}
