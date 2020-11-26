<?php
/**
 * Created by EverdreamSoft.
 * User: Shaban Shaame
 * Date: 26.11.20
 * Time: 17:12
 */

namespace CsSog;


class SogRarity extends sogCardAttributes
{

    public const SPELL_NAME = 'title';



    public $dictionary=array(
        "title"=>"title"


    );

    public function getDisplay($dictionary = null){

        if(!$dictionary) $dictionary = $this->dictionary ;
        $response = array();

        $response['id'] = $this->subjectConcept->idConcept;

        foreach ($this->dictionary ? $dictionary : array() as $key => $value){

            $response[$key] = $this->get($value);

        }

        return $response ;

    }

    public function getName(){

        return $this->get(static::SPELL_NAME);

    }





}