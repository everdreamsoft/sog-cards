<?php
/**
 * Created by EverdreamSoft.
 * User: Shaban Shaame
 * Date: 20.11.19
 * Time: 12:48
 */

namespace CsSog;


use SandraCore\EntityFactory;
use SandraCore\System;

class SogCardAttributeFactory extends EntityFactory
{



    public function __construct($bundle, System $system)
    {



        parent::__construct(static::ISA, SogCardFactory::GLOBAL_SOG_FILE, $system);


    }

}






