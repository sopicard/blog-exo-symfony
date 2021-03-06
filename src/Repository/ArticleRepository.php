<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 *
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function add(Article $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Article $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Article[] Returns an array of Article objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Article
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function searchByWord($search)
    {
        //$word = "fruit"; =>
        //->Lire les commentaires avec cette variable (valeur en dur) pour l'exercice.
        //Mais pour pouvoir r??cup ce qui sera inscrit dans le formulaire on remplace la variable $word
        //par $search en la passant dans les param de la fonction juste au-dessus et ?? l'int??rieur des %

        //->le QueryBuilder = constructeur de requ??te = cr??ation d'une requ??te SQL mais en PHP
        //parram "article" (j'aurais pu ??crire"a") = alias, surnom ou mot-cl?? d??signant la table Article
        $qb = $this->createQueryBuilder("article");

        $query = $qb->select("article")
            //on retrouve le langage SQL
            // : word -> placeholder, var SQL qui met en attente ce que l'on veut r??cup??rer (ici word)
            //afin d'??tre s??rs que la variable $word soit s??curis??e et donc utilisable telle quelle (afin d'??viter une saisie genre SELECT*FROM
            ->where("article.title LIKE :word")
            //-> % = contenu dans ce qui va ??tre tap?? par l'utilisateur. Recherche non stricte mais ??largie
            // qui permettra de trouver le "$word" inclus dans d'autres caract??res, ?? n'importe quel endroit.
            ->setParameter("word","%".$search."%")
            ->getQuery();
        //je r??cup??re le r??sultat de la requ??te que je viens de d??tailler
        return $query->getResult();
    }
}
