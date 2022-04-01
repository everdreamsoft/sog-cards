<?php
/**
 * Created by EverdreamSoft.
 * User: Shaban Shaame
 * Date: 26.11.20
 * Time: 17:31
 */

namespace CsSog;


use SandraCore\EntityFactory;
use SandraCore\System;

class SogCardFamilyFactory extends \SandraCore\EntityFactory
{
    public const ISA = 'cardFamily';
    protected  $generatedEntityClass = 'CsSog\SogCardFamily' ;
    protected const S10_XCP_FILE = 'CsSog\SogCardFamily' ;
    public const S10_BIND_CP = 'cp_asset' ;
    public const S10_CONTRACT_ID = 'cp_asset_name' ;
    public const HAS_RARITY = 'hasRarity' ;

    public const NAME = 'title';
    public const HAS_ELEMENT = 'hasElement';
    public const EXPANSION = 'belongsToExpansion';
    public const CHILD_CARD = 'sogChildCardGenerated';


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

        $factory->joinFactory(self::HAS_RARITY,$factory);
        $factory->joinPopulate();

        $factory->joinFactory(self::S10_BIND_CP,$sandra10XcpContractFactory);
        $factory->joinPopulate();
        return $factory ;

    }








}