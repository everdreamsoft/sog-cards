<?php
/**
 * Created by EverdreamSoft.
 * User: Shaban Shaame
 * Date: 17.01.20
 * Time: 17:54
 */

$loader = require_once __DIR__ . 'vendor/autoload.php'; // Autoload files using Composer autoload

$cardFact = new \CsSog\SogCardFactory("me",\CsCannon\SandraManager::getSandra());