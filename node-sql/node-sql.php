<?php
declare(strict_types=1);

class dbcon{
    protected $dsn	= "";
    protected $database = "";
    private $queryid	= "";
    private $selectid	= "";
    private $updateid	= "";
    private $insertid	= "";
    public $result = array();
    public $debug = array();
 
    
    function __construct($config){
        if($config[type] == "sqlite"){
            $this->dsn ="sqlite:".$config[file];
            $this->database = new PDO($this->dsn);
        }else if($config[type] == "mysql"){
            $this->dsn = "mysql:host=".$config[host].";dbname=".$config[dbname];
            $this->database = new PDO($this->dsn, $config[username], $config[password]);
        }
        $this->database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $this;
    }   
    
    
    function __destruct(){
       
    }
    
    
    public function db_connect($this){
        $this->database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $this->database;
    }
    private function query($sql){
        $this->database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $result = $this->database->query($sql);
        return $result;
    }
   
    public function select($sql){
        $this->database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $result = $this->database->query($sql);
        return $result;
    }
    
    public function insert($sql){
        $this->database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $result = $this->database->prepare($sql);
        return $result;
    }
    
    public function update($sql){
        $this->database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $result = $this->database->exec($sql);
        return $result;
    }
    
    public function getRecords(){return @sqlite_num_rows($this->selectid);}
    public function getLastInsertID(){return $this->database->lastInsertId();}
    public function dataSeek($pos){return sqlite_seek($this->selectid,$pos);}
    public function fetchArray($sql){return $this->select($sql);}
    public function fetchRow($sql){return $this->select($sql);}
    public function emptyResult(){return msqlite_fetch_array($this->selectid);}
    
    public function getArray($sql){
        $result = $this->select($sql);
        $i = 0;
        $data= array();
        foreach($result as $row){
            $data[] = $row;
            $i++;
        }
        return $data;
    }
    
    public function getRow($sql){
        $data = $this->fetchArray($sql);
        return $data;
    }
       
    public function updateTable($table,$id,$name,$value){
        if($this->update("UPDATE `$table` SET `$name` = '$value' WHERE `ID` =$id LIMIT 1")){
            return TRUE;
        }else{
            return FALSE;
        }
    }
    
    public function getError($msg,$sql){
        $erout =	"<div style='position:absolute;top:200px;left:500px;z-index:100;background-color: #FFFFFF;-moz-opacity:0.5;border: 1px solid red; font-size: 9pt; font-family: monospace; color: red; padding: .5em; margin: 8px;'>";
        $erout .= "<table style='font-weight: bold;color:#FF0000;-moz-opacity:0.8;'>";
        $erout .= "<tr><td>Es ist ein Fehler aufgetreten!</td></tr>";
        $erout .= "<tr><td>".$msg."</td></tr>";
        $erout .= "<tr><td>".$sql."</td></tr>";
        $erout .= "</table>";
        $erout .= "</div>";
        die($erout);
        return $erout;
    }
}


class HomegearNode extends HomegearNodeBase
{
    
    private $hg = NULL;
    private $nodeInfo = NULL;
    
    function __construct()
    {
        $this->hg = new \Homegear\Homegear();
    }
    
    function __destruct()
    {
        $this->stop();
    }
    
    public function init(array $nodeInfo) : bool
    {
        $this->nodeInfo = $nodeInfo;
        return true;
    }
    
    public function start() : bool{
        $outData=["payload" => json_decode(trim(" ".preg_replace('/\s\s+/', ' ', $this->nodeInfo["info"]["func"])),true)];
        $this->output(0,$outData);
        /* for Micha remove the line after only for this to kill immediately */
        $this->__destruct();
        return true;
    }
    
    public function stop(){}
    
    public function waitForStop(){}
    
     
    public function input(array $nodeInfoLocal, int $inputIndex, array $message)
    {
        $this->log(3, "start input hier");
        $this->nodeInfo = $nodeInfoLocal;
        $outData["payload"] = $this->nodeInfo["info"]["payload"];

        // Output ohne das es Payload ist 
        $this->output(0, $outData);
        // Output das es Payload ist
        $this->output(0,$outData["payload"]);
       
        
        return true;
    }
    
   
    
}
