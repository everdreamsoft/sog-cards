<?php
/**
 * Created by EverdreamSoft.
 * User: Shaban Shaame
 * Date: 20.11.19
 * Time: 10:41
 */

namespace CsSog ;

use CsCannon\AssetFactory;
use CsCannon\Blockchains\BlockchainContractFactory;
use CsCannon\Blockchains\Counterparty\XcpContractFactory;
use SandraCore\EntityFactory;
use SandraCore\System;

class SogCardFactory extends \SandraCore\EntityFactory
{
    public const ISA = 'sogCard';
    public const GLOBAL_SOG_FILE = 'sogFile';
    public const BELONG_TO_FAMILY = 'belongsToFamily';
    public const HAS_LEVEL = 'hasLevel';
    protected  $generatedEntityClass = 'CsSog\SogCard' ;
    protected  $collectionEntity = null ;
    protected  $collectionFactory = null ;
    protected  $contractFactory = null ; // to load all counterparty contracts to be able to bind them to cards
    protected  $bundle = null ; // to load all counterparty contracts to be able to bind them to cards


    public function __construct($bundle, System $system)
    {
        $this->bundle = $bundle ;

       // $bundle = 'sogDevFile';
        parent::__construct(self::ISA, $bundle, $system);


    }



    public static function getLoadedCardBundle($bundle, System $system){

        $factory = new SogCardFactory($bundle,$system);
        $factory->populateLocal();
        $factory->populateBrotherEntities($factory->entityReferenceContainer,AssetFactory::$file);

        SogCardFamilyFactory::getLoadedFamilyBundle($bundle,$system,$factory);


        $levelsFactory = SogCardLevelFactory::getLoadedCardLevelBundle($bundle,$system,$factory);
        //$factory->joinFactory(self::HAS_LEVEL,$family);
        $factory->joinPopulate();

        return $factory ;



}

    public  function getPopulatedCardFromId($cardId){

        $factory = new SogCardFactory($this->bundle,$this->system);
        $card = $this->last(SogCard::MOONGA_ID,$cardId);

        $factory->populateLocal();
        $factory->populateBrotherEntities($factory->entityReferenceContainer,AssetFactory::$file);

        SogCardFamilyFactory::getLoadedFamilyBundle($bundle,$system,$factory);


        $levelsFactory = SogCardLevelFactory::getLoadedCardLevelBundle($bundle,$system,$factory);
        //$factory->joinFactory(self::HAS_LEVEL,$family);
        $factory->joinPopulate();

        return $factory ;



    }

    public  function getSogCollectionEntity(){

        if (!isset($this->collectionFactory)){

            $this->collectionFactory = $assetCollectionFactory = new \CsCannon\AssetCollectionFactory($this->system);

        }

       if (!isset($this->collectionEntity)){
           $this->collectionEntity = $assetCollection = $this->collectionFactory->getOrCreate("eSog");

       }
       return $this->collectionEntity ;

    }

    public  function getXCPContractFactory():XcpContractFactory{

       if (is_null($this->contractFactory)){

           $this->contractFactory = new XcpContractFactory();
           $this->contractFactory->populateLocal();
       }
        return $this->contractFactory;

    }




    public  function getJsonLocalStorage($bundle, System $system){

        $jsonData = new SogJsonFactory($bundle,$system);
        return  $jsonData->getJsonArray();

    }

    public static  function getDiffBetweenBundle($bundleSource, $bundleDest,$system){

       $source = self::getLoadedCardBundle($bundleSource,$system);
       $dest = self::getLoadedCardBundle($bundleDest,$system);

       $sourceArray = $source->getCardArray();
       $desArray  = $dest->getCardArray();

        $diff = arrayRecursiveDiff($source->getCardArray(),$dest->getCardArray());



       return $diff ;


    }

    public  function getCardArray(){

        $entities =  $this->getEntities();
        $out = array();

        foreach($entities ? $entities : array() as $entity ){
            $out[$entity->get('moongaCardId')] = $entity->getDisplay();


        }

        return $out ;


    }



}

class SogCardFamilyFactory extends \SandraCore\EntityFactory
{
    public const ISA = 'cardFamily';
   protected  $generatedEntityClass = 'CsSog\SogCardFamily' ;
   protected const S10_XCP_FILE = 'CsSog\SogCardFamily' ;
    public const S10_BIND_CP = 'cp_asset' ;
    public const S10_CONTRACT_ID = 'cp_asset_name' ;


    public function __construct($bundle, System $system)
    {



        //$this->getTriplets();



        parent::__construct(self::ISA, $bundle, $system);
    }

    public static function getLoadedFamilyBundle($bundle, System $system, SogCardFactory $cardFactory)
    {

        $sandra10XcpContractFactory = new EntityFactory('cp_asset','cp_asset',$system);
        $sandra10XcpContractFactory->entityReferenceContainer = 'is_a';

        $factory = new SogCardFamilyFactory($bundle,$system);

        $cardFactory->joinFactory(SogCardFactory::BELONG_TO_FAMILY,$factory);
        $cardFactory->joinPopulate();

        $factory->joinFactory(self::S10_BIND_CP,$sandra10XcpContractFactory);
        $factory->joinPopulate();
        return $factory ;

    }








}



class SogCardLevelFactory extends \SandraCore\EntityFactory
{
    public const ISA = 'sogCardLevel';
    public const HAS_SPELL = 'hasSpells';
    public const SPELL_STRENGTH = 'spellStrength';
   // public const HAS_FAMILY = 'belongsToFamily';
    protected  $generatedEntityClass = 'CsSog\SogCardLevel' ;



    public function __construct($bundle, System $system)
    {


        parent::__construct(self::ISA, $bundle, $system);


    }

    public static function getLoadedCardLevelBundle($bundle, System $system, SogCardFactory $cardFactory){

        $factory = new SogCardLevelFactory($bundle,$system);

        $cardFactory->joinFactory(SogCardFactory::HAS_LEVEL,$factory);
        $cardFactory->joinPopulate();

        $factory->populateBrotherEntities(static::HAS_SPELL);

        //get spell data


        $spells = new SogSpellFactory($bundle,$system);
        $factory->joinFactory(self::HAS_SPELL,$spells);
        $factory->joinPopulate();




        return $factory ;



    }

}

function arrayRecursiveDiff($aArray1, $aArray2) {
    $aReturn = array();

    foreach ($aArray1 as $mKey => $mValue) {
        if (array_key_exists($mKey, $aArray2)) {
            if (is_array($mValue)) {
                $aRecursiveDiff = arrayRecursiveDiff($mValue, $aArray2[$mKey]);
                if (count($aRecursiveDiff)) { $aReturn[$mKey] = $aRecursiveDiff; }
            } else {
                if ($mValue != $aArray2[$mKey]) {
                    $aReturn[$mKey] = $mValue;
                }
            }
        } else {
            $aReturn[$mKey] = $mValue;
        }
    }

    return $aReturn;
}