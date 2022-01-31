<?php
/**
 * Created by EverdreamSoft.
 * User: Shaban Shaame
 * Date: 26.11.20
 * Time: 17:27
 */

namespace CsSog;

use SandraCore\Entity;

class SogCardFamily extends Entity
{
    public function getRarity()
    {
        $rarity = $this->getBrotherEntity(SogCardFamilyFactory::HAS_RARITY);
        /** @var Entity $rarity */
        if (!empty($rarity)) {
            $rarity = end($rarity);
            $rarityShortName = $this->system->systemConcept->getSCS($rarity->targetConcept->idConcept);
            return $this->transformRarityName($rarityShortName);
        };
        return null;
    }

    public function getExtension()
    {
    }

    public function transformRarityName($rarityShortname)
    {
        switch ($rarityShortname) {
            case 'rarityRare' :
                return 'Rare';
            case 'rarityEpic' :
                return 'Epic';
            case 'rarityLegendary' :
                return 'Legendary';
            case 'rarityCommon' :
                return 'Common';
        }
    }

}
