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

    /**
     * Get possible redirects (matching or containing regular expressions)
     *
     * @param string $hostname
     * @param string|null $path
     *
     * @return array
     */
    public function getPossibleRedirects($hostname, $path = null) {
        return $this->createQueryBuilder('r')
            ->where('r.useRegex = true')
            ->orWhere('r.hostname = :hostname AND (r.path = :path OR r.path IS NULL)')
            ->orderBy('r.position')
            ->setParameter('hostname', $hostname)
            ->setParameter('path', $path)
            ->getQuery()
            ->getResult();
    }

    public function setNewPosition(Redirect $redirect, $position = 0) {
        $this->createQueryBuilder('r')
            ->update('r.position = r.position + 1')
            ->where('r.position >= :position')
            ->setParameter('position', $position)
            ->getQuery()
            ->execute();

        $redirect->setPosition($position);
        $em = $this->getEntityManager();
        $em->persist($redirect);
        $em->flush();
    }
}
