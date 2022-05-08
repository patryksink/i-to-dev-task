<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use App\Entity\UserLogin;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserLogin|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserLogin|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserLogin[]    findAll()
 * @method UserLogin[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method UserLogin|null findByUser(User $user)
 */
class UserLoginRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserLogin::class);
    }
}
