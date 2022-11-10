<?php
/**
 * Created by EverdreamSoft.
 * User: Shaban Shaame
 * Date: 26.11.19
 * Time: 11:22
 */

namespace CsSog;

use SandraCore\DebugStack;

class Sandra7Bridge extends \SandraCore\System {

    public static $logger = null;

    public function __construct($env = '', $install = false, $dbHost = '127.0.0.1', $db = 'sandra', $dbUsername = 'root', $dbpassword = '')
    {
        $debugStack = new DebugStack();
        $debugStack->enabled = false;
        self::$logger = $debugStack;

        self::$pdo = new \SandraCore\PdoConnexionWrapper($dbHost, $db,$dbUsername, $dbpassword);
        $pdoWrapper = self::$pdo ;

        $prefix = '' ;
        $this->tablePrefix = $prefix ;
        $suffix = '';
        if($env)
            $suffix = '_'.$env;

        $this->conceptTable = $prefix .'Concept' . $suffix;
        $this->linkTable =  $prefix .'Link' . $suffix;
        $this->tableReference =  $prefix .'References' . $suffix;
        $this->tableStorage =  $prefix .'aux_dataStorage' . $suffix;
        $this->tableConf =  $prefix .'system_configuration' . $suffix;

        if ($install) $this->install();


        $this->systemConcept = new \SandraCore\SystemConcept($pdoWrapper, self::$logger, $this->conceptTable);

        $this->deletedUNID = $this->systemConcept->get('deleted');
        //die("on system deleted ".$this->deletedUNID);

        self::$logger->connectionInfo = array('Host' => $pdoWrapper->host, 'Database' => $pdoWrapper->database, 'Sandra environment' => $env);

        $this->factoryManager = new \SandraCore\FactoryManager($this);
        $this->conceptFactory = new \SandraCore\ConceptFactory($this);

        $this->instanceId = rand(0,999)."-".rand(0,9999)."-".rand(0,999);


    }


}