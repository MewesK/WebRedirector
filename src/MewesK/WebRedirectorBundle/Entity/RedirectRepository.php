<?php

namespace MewesK\WebRedirectorBundle\Entity;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityRepository;

class RedirectRepository extends EntityRepository
{
    /**
     * Get the next available position
     *
     * @return int
     */
    public function getNextAvailablePosition() {
        $lastPosition = $this->createQueryBuilder('r')
            ->select('MAX(r.position)')
            ->getQuery()
            ->getSingleResult(AbstractQuery::HYDRATE_SINGLE_SCALAR);
        return is_null($lastPosition) ? 0 : intval($lastPosition) + 1;
    }
}
