<?php
/**
 * Created by EverdreamSoft.
 * User: Shaban Shaame
 * Date: 26.11.20
 * Time: 17:33
 */

namespace CsSog;


use SandraCore\System;

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