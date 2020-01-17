<?php
/**
 * Created by EverdreamSoft.
 * User: Shaban Shaame
 * Date: 21.11.19
 * Time: 09:55
 */

namespace CsSog;


use SandraCore\EntityFactory;
use SandraCore\System;

class SogJsonFactory extends EntityFactory
{

    protected  $generatedEntityClass = 'CsSog\SogCard' ;
    protected $conceptShortname = 'sogCardJSON';
    private $bundledJson = null ;

    public function __construct($bundle, System $system)
    {

        //we find the concept called 'sogCardJSON' and we set the factory to load only this one
        $jsonConcept = $this->conceptArray =array($system->conceptFactory->getConceptFromShortnameOrId($this->conceptShortname)->idConcept);

        parent::__construct(null, $bundle, $system);

        $this->populateLocal();

        //now we should have only one json
        $entities = $this->getEntities();
        $entity = end($entities);
        $this->bundledJson = $entity->getStorage();


    }

    public function getJsonArray()
    {

      return json_decode($this->bundledJson,1);


    }


}