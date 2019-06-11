<?php
declare(strict_types=1);


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
    
   /* For Micha and Later ;) 
     
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
    
    */
    
}
