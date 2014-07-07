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

    /**
     * @param Redirect $redirect
     * @param int $position
     */
    public function setNewPosition(Redirect $redirect, $position = 0) {
        $firstPosition = $position <= $redirect->getPosition() ? $position : $redirect->getPosition();
        $lastPosition = $position >= $redirect->getPosition() ? $position : $redirect->getPosition();

        /** @var $entries Redirect[] */
        $entries = $this->createQueryBuilder('r')
            ->where('r.position >= :firstPosition')
            ->andWhere('r.position <= :lastPosition')
            ->andWhere('r.position != :currentPosition')
            ->orderBy('r.position')
            ->setParameter('firstPosition', $firstPosition)
            ->setParameter('lastPosition', $lastPosition)
            ->setParameter('currentPosition', $redirect->getPosition())
            ->getQuery()
            ->getResult();

        if (count($entries) == 0) {
            return;
        }

        array_splice($entries, $position - $entries[0]->getPosition(), 0, array($redirect));

        $em = $this->getEntityManager();

        for ($i = 0; $i < count($entries); $i++) {
            $entry = $entries[$i];
            $entry->setPosition($firstPosition + $i);
            $em->persist($entry);
        }

        $em->flush();
    }

    /**
     * @return array
     */
    public function findAllOrderedByPosition() {
        return $this->findBy(array(), array('position' => 'ASC'));
    }
}
