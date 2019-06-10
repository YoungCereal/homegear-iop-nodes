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
        $this->__destruct();
        return true;
    }
    
    public function stop(){}
    
    public function waitForStop(){}
    
}
