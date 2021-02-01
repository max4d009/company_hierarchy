<?php

namespace App\Repository;


use App\Entity\Asteroid;
use App\NasaApi\Dto\Request\Filter\BaseSort;
use App\NasaApi\Dto\Request\Filter\FilterAbstract;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Asteroid|null find($id, $lockMode = null, $lockVersion = null)
 * @method Asteroid|null findOneBy(array $criteria, array $orderBy = null)
 * @method Asteroid[]    findAll()
 * @method Asteroid[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AsteroidRepository extends RepositoryAbstract
{
    /**
     * AsteroidRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Asteroid::class);
    }


    /**
     * @param FilterAbstract[] $filters
     * @param BaseSort[] $sortList
     * @param int $limit
     * @param int $offset
     * @return Asteroid[]
     */
    public function getAsteroidsByFilters(array $filters, array $sortList, int $limit, int $offset)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->from('App:Asteroid', 'a');
        $qb->select('a');

        if($limit) $qb->setMaxResults($limit);
        if($offset) $qb->setFirstResult($offset);

        $this->injectFilters($qb, $filters, 'a');
        $this->injectSort($qb, $sortList, 'a');
        return $qb->getQuery()->getResult();
    }

}
