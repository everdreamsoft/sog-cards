<?php
/**
 * Created by EverdreamSoft.
 * User: Shaban Shaame
 * Date: 26.11.20
 * Time: 17:13
 */

namespace CsSog;


use SandraCore\Entity;

class SogCardLevel extends Entity
{



    public $dictionary=array(
        "cardAttack"=>"cardAttack",
        "cardHealth"=>"cardHealth",
        "cardSpeed"=>"cardSpeed",

    );

    public function getDisplay($dictionary = null){

        if(!$dictionary) $dictionary = $this->dictionary ;
        $response = array();

        $response['unid'] = $this->subjectConcept->idConcept;

        foreach ($this->dictionary ? $dictionary : array() as $key => $value){

            $data[$key] = $this->get($value);

        }

        $spells =$this->getSpells();

        foreach ($spells ? $spells : array() as $spells){
            //  $data['spellArray'][] = $spells->getDisplay();

        }

        //this seems to be fixed
        $data['upgradeCost'] = -1;
        $data['crystallizeValue'] = -1;

        $data['spell'] = $this->getSpellName();
        $data['spellStrength'] = $this->getSpellStrength();



        $response = $data ;



        return $response ;

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