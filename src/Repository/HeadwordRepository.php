<?php

namespace App\Repository;

use App\Entity\Headword;
use Doctrine\ORM\EntityRepository;

class HeadwordRepository extends EntityRepository
{
    public function findHeadwordsByCampaign($campaignId)
    {
        return $this->findBy([
            'campaign' => $campaignId
        ], [
            'headwordName' => 'ASC'
        ]);
    }
}
