<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 *
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }


    public function findAll(): array
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

   /*
    public function search(string $value)
        {
            // correspond au SELECT * From Post as p
            $query = $this->createQueryBuilder('p')
                ->andWhere('p.title LIKE :val')
                //  % est un caractère joker qui remplace tous les autres caractères en sql
                ->setParameter('val', "%".$value."%")
                ->orderBy('p.id', 'ASC')
                ->getQuery();
                

            return $query->getResult();
        }
        */
       
        public function search(string $value)
        {
            //SELECT * FROM POST as p
           $query = $this->createQueryBuilder('p')
                ->join("p.user", "u")
                ->andWhere("p.title LIKE :val")
                ->orWhere("p.content LIKE :val")
                ->orWhere("u.name LIKE :val")
                ->setParameter('val', "%".$value."%")
                ->orderBy('p.id', 'ASC')
                ->getQuery();
    
           return $query->getResult();
        }
       



//    /**
//     * @return Post[] Returns an array of Post objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Post
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
