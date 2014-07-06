<?php

namespace MewesK\WebRedirectorBundle\Entity;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityRepository;

class RedirectRepository extends EntityRepository
{
    public function setPositionAndPersist(Redirect $redirect) {
        $lastPosition = $this->createQueryBuilder('r')
            ->select('MAX(r.position)')
            ->getQuery()
            ->getSingleResult(AbstractQuery::HYDRATE_SINGLE_SCALAR);
        $nextPosition = is_null($lastPosition) ? 0 : intval($lastPosition) + 1;
        $redirect->setPosition($nextPosition);
        $this->getEntityManager()->persist($redirect);
    }
}
