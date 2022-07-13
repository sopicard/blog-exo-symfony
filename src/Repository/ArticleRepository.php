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
        //Mais pour pouvoir récup ce qui sera inscrit dans le formulaire on remplace la variable $word
        //par $search en la passant dans les param de la fonction juste au-dessus et à l'intérieur des %

        //->le QueryBuilder = constructeur de requête = création d'une requête SQL mais en PHP
        //parram "article" (j'aurais pu écrire"a") = alias, surnom ou mot-clé désignant la table Article
        $qb = $this->createQueryBuilder("article");

        $query = $qb->select("article")
            //on retrouve le langage SQL
            // : word -> placeholder, var SQL qui met en attente ce que l'on veut récupérer (ici word)
            //afin d'être sûrs que la variable $word soit sécurisée et donc utilisable telle quelle (afin d'éviter une saisie genre SELECT*FROM
            ->where("article.title LIKE :word")
            //-> % = contenu dans ce qui va être tapé par l'utilisateur. Recherche non stricte mais élargie
            // qui permettra de trouver le "$word" inclus dans d'autres caractères, à n'importe quel endroit.
            ->setParameter("word","%".$search."%")
            ->getQuery();
        //je récupère le résultat de la requête que je viens de détailler
        return $query->getResult();
    }
}
