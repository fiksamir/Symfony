<?php

namespace App\Repository;

use App\Entity\Answer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Answer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Answer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Answer[]    findAll()
 * @method Answer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnswerRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Answer::class);
    }

    public static function createApprovedCriteria(): Criteria
    {
        return Criteria::create()
            ->andWhere(Criteria::expr()->eq('status', Answer::APPROVED_STATUS));
    }

    public function findAllApproved(int $max = 10): array
    {
        return $this->createQueryBuilder('a')
            ->addCriteria(static::createApprovedCriteria())
            ->innerJoin('a.question', 'q')
            ->addSelect('q')
            ->setMaxResults($max)
            ->addOrderBy('a.votes', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findMostPopular(string $search = null): array
    {
        $qb = $this->createQueryBuilder('a')
            ->addCriteria(static::createApprovedCriteria())
            ->innerJoin('a.question', 'q')
            ->addSelect('q')
            ->addOrderBy('a.votes', 'DESC')
        ;

        if ($search !== null) {
            $qb->andWhere('a.content LIKE :search')
                ->setParameter('search', '%' . $search . '%')
            ;
        }

        return $qb
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
}
