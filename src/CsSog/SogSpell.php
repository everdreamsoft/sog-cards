<?php
/**
 * Created by EverdreamSoft.
 * User: Shaban Shaame
 * Date: 26.11.20
 * Time: 17:11
 */

namespace CsSog;


class SogSpell extends SogCardAttributes
{

    public const SPELL_NAME = 'title';



    public $dictionary=array(
        "title"=>"title"


    );

    public function getName(){

        return $this->get(static::SPELL_NAME);

    }

    public function getDisplay($dictionary = null){

        if(!$dictionary) $dictionary = $this->dictionary ;
        $response = array();

        $response['id'] = $this->subjectConcept->idConcept;

        foreach ($this->dictionary ? $dictionary : array() as $key => $value){

            $response[$key] = $this->get($value);

        }

        return $response ;

    }



}