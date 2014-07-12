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
            ->where('r.enabled = true')
            ->andWhere('(r.useRegex = true OR (r.hostname = :hostname AND (r.path = :path OR r.path IS NULL)))')
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

        /** @var $entities Redirect[] */
        $entities = $this->createQueryBuilder('r')
            ->where('r.position >= :firstPosition')
            ->andWhere('r.position <= :lastPosition')
            ->andWhere('r.position != :currentPosition')
            ->orderBy('r.position')
            ->setParameter('firstPosition', $firstPosition)
            ->setParameter('lastPosition', $lastPosition)
            ->setParameter('currentPosition', $redirect->getPosition())
            ->getQuery()
            ->getResult();

        if (count($entities) == 0) {
            return;
        }

        array_splice($entities, $position - $entities[0]->getPosition(), 0, array($redirect));

        $em = $this->getEntityManager();

        for ($i = 0; $i < count($entities); $i++) {
            $entity = $entities[$i];
            $entity->setPosition($firstPosition + $i);
            $em->persist($entity);
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
