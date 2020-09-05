<?php declare(strict_types=1);

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

final class User extends EntityRepository
{
    public function countActive(): int
    {
        return (int)$this->createQueryBuilder('t')
            ->select('count(t.id)')
            ->where('t.isEnabled=1')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
