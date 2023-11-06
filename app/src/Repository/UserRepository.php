<?php

namespace App\Repository;

use App\Entity\RoleGroup;
use App\Entity\User\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends AbstractBasicRepository implements UserLoaderInterface
{
    public function __construct(protected readonly ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function loadUserByIdentifier(string $identifier): ?UserInterface
    {
        $user = $this->findOneBy(['email' => $identifier, 'deleted' => false]);

        if (!empty($user) && !$this->isUnemployed($user)) {
            return null;
        }

        return $user;
    }

    private function isUnemployed(User $user): bool
    {
        $roleGroups = [];

        foreach ($user->getRoleGroups() as $group) {
            $roleGroups[] = $group->getName();
        }

        if (
            in_array(RoleGroup::NAME_ROLE_GROUP_EMPLOYEE, $roleGroups)
            && empty($user->getOrganizationsEmployee())
        )
        {
            return false;
        }

        return true;
    }
}
