<?php
declare(strict_types=1);

define("DEBUG_PUSHOVER", true);
// api url
const API_URL = 'https://api.pushover.net/1/messages.xml';
//validation url
const VALIDATION_URL = 'https://api.pushover.net/1/users/validate.json';
//sounds URL
const SOUNDS_URL = 'https://api.pushover.net/1/sounds.json?token=%s';

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
    
    public function start() : bool{}
    
    public function stop(){}
    
    public function waitForStop(){}
    
     
    public function input(array $nodeInfoLocal, int $inputIndex, array $message)
    {
      
        $this->nodeInfo = $nodeInfoLocal;
        if ( ENABLE_UPLOADS ){
            $this->log(3, "Pushover AppTooken:".$this->nodeInfo["info"]["apptoken"]);
            $this->log(3, "Pushover UserTooken:".$this->nodeInfo["info"]["usertoken"]);
            $this->log(3, "Pushover Device:".$this->nodeInfo["info"]["device"]);
            $this->log(3, "Pushover Title:".$this->nodeInfo["info"]["header"]);
            $this->log(3, "Pushover Message:".$this->nodeInfo["info"]["message"]);
            $this->log(3, "Pushover Priority:".$this->nodeInfo["info"]["priority"]);
            $this->log(3, "Pushover Image:".$this->nodeInfo["info"]["image"]);
            $this->log(3, "Pushover Sound:".$this->nodeInfo["info"]["sound"]);
            
        }
        
        
        
        if($this->nodeInfo["info"]["image"] !=""){
        $msgarray=array( 
            "token" => $this->nodeInfo["info"]["apptoken"],
            "user" =>$this->nodeInfo["info"]["usertoken"],
            "message" => $this->nodeInfo["info"]["message"],
            "attachment"=> curl_file_create($this->nodeInfo["info"]["image"], "image/png"),
            "timestamp" => time(),
            "title" => $this->nodeInfo["info"]["title"]
            
        );
        }else{
          $msgarray=array(
            "token" => $this->nodeInfo["info"]["apptoken"],
            "user" =>$this->nodeInfo["info"]["usertoken"],
            "message" => $this->nodeInfo["info"]["message"],
            "timestamp" => time(),
            "title" => $this->nodeInfo["info"]["titles"]
            
            );
        }
        if($this->nodeInfo["info"]["sound"] != ""){
            array_push($msgarray,'sound', $this->nodeInfo["info"]["sound"]);
        }
        
        if($this->nodeInfo["info"]["html"]){
            array_push($msgarray,'html', $this->nodeInfo["info"]["html"]);
        }
        
        if($this->nodeInfo["info"]["device"]){
            array_push($msgarray,'device', $this->nodeInfo["info"]["device"]);
        }
            
        if($this->nodeInfo["info"]["priority"]){
            array_push($msgarray,'priority', $this->nodeInfo["info"]["priority"]);
        }else{
            array_push($msgarray,'priority',2);
        }
        
        if($this->nodeInfo["info"]["expire"] != ""){
            array_push($msgarray,'expire', $this->nodeInfo["info"]["expire"]);
        }
        
        if($this->nodeInfo["info"]["retry"] != ""){
            array_push($msgarray,'retry', $this->nodeInfo["info"]["retry"]);
        }
            
        if($this->nodeInfo["info"]["callback"] != ""){
            array_push($msgarray,'callback', $this->nodeInfo["info"]["callback"]);
        }
        
        if($this->nodeInfo["info"]["url"] != ""){
            array_push($msgarray,'url', $this->nodeInfo["info"]["url"]);
        }
        

        if($this->nodeInfo["info"]["url_title"] != ""){
            array_push($msgarray,'url_title', $this->nodeInfo["info"]["url_title"]);
        }

        
        curl_setopt_array($ch = curl_init(), array(
            CURLOPT_URL => "https://api.pushover.net/1/messages.json",
            CURLOPT_POSTFIELDS =>$msgarray,
            CURLOPT_SAFE_UPLOAD => true,
            CURLOPT_RETURNTRANSFER => true,
        ));
        curl_exec($ch);
        curl_close($ch);
        return true;
    }
}

?>