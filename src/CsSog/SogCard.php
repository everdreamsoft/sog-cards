<?php
/**
 * Created by EverdreamSoft.
 * User: Shaban Shaame
 * Date: 20.11.19
 * Time: 11:26
 */

namespace CsSog;


use CsCannon\Asset;
use CsCannon\AssetFactory;
use CsCannon\SandraManager;
use SandraCore\Entity;
use SandraCore\EntityFactory;
use SandraCore\System;

class SogCard extends Asset
{

    public $dictionary=array(
        "name"=>"title",
        "evolutionRank"=>"evolutionRank",
        "moongaId"=>"moongaCardId",

    );

    public const MOONGA_ID = 'moongaCardId' ;
    public const SOG_CODE = 'sogCardIndex' ; //the new moongaId
    public const EVOLUTION_RANK = 'evolutionRank' ; //the new moongaId
    public const NAME = 'title' ; //the new moongaId
    public const MAX_SUPPLY = 'maxSupply' ; //the new moongaId
    public const ETHEREUM_SUPPLY = 'ethSupply' ; //the new moongaId
    public const COUNTERPARTY_SUPPLY = 'xcpSupply' ; //the new moongaId
    public const STORY = 'story_' ; //lanuague
    public const SUPPORTED_LANGUAGES = ['en'] ;

    public function getDisplay($dictionary = null){

        if(!$dictionary) $dictionary = $this->dictionary ;
        $response = array();
        $data = array();

        $data['unid'] = $this->subjectConcept->idConcept;


        //$family = $this->getFamily();


        //$data['family'] =$family->subjectConcept->idConcept;

        //if ($this->subjectConcept->idConcept != 608376) return null ;

        foreach ($this->dictionary ? $dictionary : array() as $key => $value){

            $data[$key] = $this->get($value);

        }

        $levels =$this->getCardLevels();

        foreach ($levels ? $levels : array() as $level){
            if (!($level instanceof SogCardLevel)) continue ;
            $data['levels'][] = $level->getDisplay();

        }

        try {
            $family = $this->getFamily();
            $response['rarity'] = $family->getRarity();

        }catch (\Exception $exception){

        }

        $response = $data ;

        return $response ;

    }

    public function getCardLevels()
    {
        return $this->getJoinedEntities(SogCardFactory::HAS_LEVEL);
    }

    public function getFactory():SogCardFactory
    {
        return $this->factory ;
    }

    public function getEvolutionRank()
    {
        return $this->get(self::EVOLUTION_RANK);
    }

    public function getMoongaId()
    {
        return $this->get(self::MOONGA_ID);
    }

    public function getFamily():SogCardFamily
    {

        $singleFamily = $this->getJoinedEntities(SogCardFactory::BELONG_TO_FAMILY);
        //A card must have one and only one familly
        return end($singleFamily);
    }

    public function setCSCannonAsset(){

        if ($this->system !== SandraManager::getSandra()){
            SandraManager::setSandra($this->system);

        }

        //do we have a level 3 card ?
        // if ($this->getEvolutionRank() != 3) return ;

        $collectionEntity = $this->getFactory()->getSogCollectionEntity();

        $contractFactory = $this->getFactory()->getXCPContractFactory();
        //$contract = $contractFactory->get()

        if ($this->getBrotherEntity($this->factory->entityReferenceContainer, AssetFactory::$file) == null){

            $data['name'] = $this->get('title');
            $data[self::SOG_CODE] = $this->getMoongaId();
            $data[AssetFactory::ID] = 'SOG-'.$this->getMoongaId();

            $xcpContractId = $this->getS10XCPAssetName();


            $this->setBrotherEntity($this->factory->entityReferenceContainer, AssetFactory::$file,$data);
            $this->setBrotherEntity('is_a', AssetFactory::$isa,null);
            $this->setBrotherEntity(AssetFactory::$collectionJoinVerb,$collectionEntity,null);

            if (isset($xcpContractId)){
                $contractEntity = $contractFactory->get($xcpContractId,true);
                $this->setBrontherEntity(AssetFactory::$tokenJoinVerb,$contractEntity,null);
                $contractEntity->bindToCollection($collectionEntity); //and we bind the contract to the collection

            }

            //remove wrongly added assets





        }

        else if ($this->getEvolutionRank() != 3){
            /** @var Entity $notAnAssetAnymore */
            $notAnAssetAnymore = $this->getBrotherEntity($this->factory->entityReferenceContainer, AssetFactory::$file);
            $notAnAssetAnymore->delete();

        }

    }

    public  function getS10XCPAssetName()
    {

        $s10XcpContractEntities = $this->getFamily()->getJoinedEntities(SogCardFamilyFactory::S10_BIND_CP);
        if (!is_array($s10XcpContractEntities)) return null ;
        $s10XcpContractEntity = end ($s10XcpContractEntities);
        $output = null ;
        if ($s10XcpContractEntity){
            /** @var Entity $s10XcpContractEntity */
            $output = $s10XcpContractEntity->get(SogCardFamilyFactory::S10_CONTRACT_ID);

        }

        return $output ;


    }

    public  function getName()
    {

        return $this->get(self::NAME);


    }

    public  function setMaxSupply(int $supply)
    {

        return $this->createOrUpdateRef(self::MAX_SUPPLY,$supply);


    }

    public  function getMaxSupply()
    {

        return $this->get(self::MAX_SUPPLY);


    }

    public  function setEthereumSupply(int $supply)
    {

        return $this->createOrUpdateRef(self::ETHEREUM_SUPPLY,$supply);


    }

    public  function getEthereumSupply()
    {

        return $this->get(self::ETHEREUM_SUPPLY);


    }

    public  function setCounterpartySupply(int $supply)
    {

        return $this->createOrUpdateRef(self::COUNTERPARTY_SUPPLY,$supply);


    }

    public  function getCounterpartySupply()
    {

        return $this->get(self::COUNTERPARTY_SUPPLY);


    }

    public  function setStory($languange,$story)
    {

        if (!in_array($languange,self::SUPPORTED_LANGUAGES)) return false ;

        $storyStorage = $this->getBrotherEntity('has_storage',self::STORY.$languange);
        if (!$storyStorage) $storyStorage = $this->setBrotherEntity('has_storage',self::STORY.$languange,null);



        return $storyStorage->setStorage($story);


    }

    public  function getStory($languange)
    {

        if (!in_array($languange,self::SUPPORTED_LANGUAGES)) return null ;

        $storyStorage = $this->getBrotherEntity('has_storage',self::STORY.$languange);

        if (!$storyStorage) return null ;

        /** @var Entity $storyStorage */

        return $storyStorage->getStorage();


    }





}



