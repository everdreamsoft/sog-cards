<?php
/**
 * Created by EverdreamSoft.
 * User: Shaban Shaame
 * Date: 26.11.20
 * Time: 17:13
 */

namespace CsSog;


use SandraCore\Entity;
use SandraCore\System;

class SogCardLevel extends Entity
{



    public $dictionary=array(
        "cardAttack"=>"cardAttack",
        "cardHealth"=>"cardHealth",
        "cardSpeed"=>"cardSpeed",

    );

    public function populateWithEntities(System $sandra, string $bundle): SogCardLevel
    {
        $this->factory->joinFactory(SogCardLevelFactory::HAS_SPELL, new SogSpellFactory($bundle, $sandra));
        $this->factory->joinPopulate();
        $this->factory->populateBrotherEntities(SogCardLevelFactory::HAS_SPELL);

        return $this;
    }

    public function getDisplay($dictionary = null): array
    {

        if(!$dictionary) $dictionary = $this->dictionary ;
        $response = array();

        $response['unid'] = $this->subjectConcept->idConcept;

        foreach ($this->dictionary ? $dictionary : array() as $key => $value){

            $data[$key] = $this->get($value);
        }

        //this seems to be fixed
        $data['upgradeCost'] = -1;
        $data['crystallizeValue'] = -1;

        $data['spell'] = $this->getSpellName();
        $data['spellStrength'] = $this->getSpellStrength();

        return $data;

    }

    public function getSpells()
    {

        return $this->getJoinedEntities(SogCardLevelFactory::HAS_SPELL);


    }

    public function getSpellName()
    {

        $spellsArray =$this->getJoinedEntities(SogCardLevelFactory::HAS_SPELL);
        if (!is_array($spellsArray)) return 'null';

        $spell = end($spellsArray);

        if (!($spell instanceof SogSpell)) {
            return "none";

        }


        /** @var SogSpell $spell */

        return $spell->getName();


    }

    public function getSpellStrength()
    {

        $spellArray = $this->getBrotherEntity(SogCardLevelFactory::HAS_SPELL);

        if (!is_array($spellArray)) return 'null';

        $brotherSpell = reset($spellArray);

        return $brotherSpell->get(SogCardLevelFactory::SPELL_STRENGTH);


    }



}