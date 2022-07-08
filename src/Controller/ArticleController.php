<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;

class ArticleController extends AbstractController
{
//    /**
//     * @Route("article/{id}", name="article")
//     */
//    public function article($id)
//    {
//        $article = [
//            1 => [
//                'id' => 1,
//                'title' => 'Non, là c\'est sale',
//                'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam amet assumenda deserunt eius eveniet molestias necessitatibus non, quos sed sequi! Animi aspernatur assumenda earum laudantium odio quasi quibusdam quisquam veniam.',
//                'publishedAt' => new \DateTime('NOW'),
//                'isPublished' => true,
//                'author' => 'Eric',
//                'image' => 'https://media.gqmagazine.fr/photos/5b991bbe21de720011925e1b/master/w_780,h_511,c_limit/la_tour_montparnasse_infernale_1893.jpeg'
//            ],
//            2 => [
//                'id' => 2,
//                'title' => 'Il faut trouver tous les gens qui étaient de dos hier',
//                'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam amet assumenda deserunt eius eveniet molestias necessitatibus non, quos sed sequi! Animi aspernatur assumenda earum laudantium odio quasi quibusdam quisquam veniam.',
//                'publishedAt' => new \DateTime('NOW'),
//                'isPublished' => true,
//                'author' => 'Maurice',
//                'image' => 'https://fr.web.img6.acsta.net/r_1280_720/medias/nmedia/18/35/18/13/18369680.jpg'
//            ],
//            3 => [
//                'id' => 3,
//                'title' => 'Pluuutôôôôt Braaaaaach, Vasarelyyyyyy',
//                'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam amet assumenda deserunt eius eveniet molestias necessitatibus non, quos sed sequi! Animi aspernatur assumenda earum laudantium odio quasi quibusdam quisquam veniam.',
//                'publishedAt' => new \DateTime('NOW'),
//                'isPublished' => true,
//                'author' => 'Didier',
//                'image' => 'https://media.gqmagazine.fr/photos/5eb02109566df9b15ae026f3/master/pass/n-3freres.jpg'
//            ],
//            4 => [
//                'id' => 4,
//                'title' => 'Quand on attaque l\'empire, l\'empire contre attaque',
//                'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam amet assumenda deserunt eius eveniet molestias necessitatibus non, quos sed sequi! Animi aspernatur assumenda earum laudantium odio quasi quibusdam quisquam veniam.',
//                'publishedAt' => new \DateTime('NOW'),
//                'isPublished' => true,
//                'author' => 'Mbala',
//                'image' => 'https://fr.web.img2.acsta.net/newsv7/21/01/20/15/49/5077377.jpg'
//            ]
//        ];
//        return $this->render("article.html.twig", ["article" => $article[$id]]);
//    }
//
//   // je crée une nouvelle route + méthode pour créer une nouvel article dans ma table existante article
    /**
     * @Route("insert-article", name="insert_article")
     */
    public function insertArticle(EntityManagerInterface $entityManager)
    {
        // je crée une variable dans laquelle je mets une nouvelle instance de mon entité Article
        $article = new Article();
        // et je définis les nouvelles données grâce à l'instance de classe que j'ai appelée au-dessus
        $article->setTitle("Nouveau titre");
        $article->setIsPublished(true);
        $article->setAuthor("Nouvel auteur");
        $article->setContent("Lorem ipsum");
        //ensuite je vais créer une var entityManager contenant
        // l'instance de classe EntityManagerInterface
        // le tout mis en paramètres de ma fonction insertArticle(version raccourcie du mot clé "new")

        //pour finir je pré enregistre mon nouvel article avec la fonction persist
        //et je l'envoie à la db avec la fonction flush
        $entityManager->persist($article);
        $entityManager->flush();

        $article1 = new Article();
        $article1->setTitle("Bienvenue");
        $article1->setIsPublished(true);
        $article1->setAuthor("So");
        $article1->setContent("Lorem ipsum");

        $entityManager->persist($article1);
        $entityManager->flush();

        dd($article1);

    }
    // je commente la première partie (ci-dessus) avec le fake tableau de db
    //je crée une nouvelle route article avec une nouvelle méthode associée => afficher article en fonction id
    // (en SQL = SELECT * FROM)
    //je mets en paramètres de ma méthode une instance de classe ArticleRepository associée à var du même nom
    //ce qui me permet de me servir des propriétés de la classe repository => récupération de données
    // find => spécifique pour récup id
    /**
     * @Route("article/{id}", name="article")
     */
    public function showArticle($id, ArticleRepository $articleRepository)
    {
        $article = $articleRepository->find($id);

        return $this->render("article.html.twig", ["article" => $article]);
    }

    /**
     * @Route("articlesList", name="articlesList")
     */
    public function showArticles(ArticleRepository $articlesRepository)
    {
        $articlesList = $articlesRepository->findall();

        return $this->render("articlesList.html.twig", ["articlesList" => $articlesList]);
    }
    //méthode de suppression => mix Repository et Entity
    /**
     * @Route("articlesList/delete/{id}", name="delete_article")
     */
    public function deleteArticle($id, ArticleRepository $articleRepository, EntityManagerInterface $entityManager)
    {
        $article=$articleRepository->find($id);
    //ajout d'une condition pour article à supprimer et pour un déjà supprimé
        if(!is_null($article)) {
            $entityManager->remove($article);
            $entityManager->flush();

            return new Response("Removed !");
        }else{
            return new Response("Already removed !!");
        }
    }
    //méthode de mise à jour
    /**
     * @Route("articlesList/update/{id}", name="update_article")
     */
    public function updateArticle($id, ArticleRepository $articleRepository, EntityManagerInterface $entityManager)
    {
        $article=$articleRepository->find($id);
        //les valeurs à modifier
        $article->setTitle("title updated");
        $article->setContent("test update");

        $entityManager->persist($article);
        $entityManager->flush();
        //la réponse affichée sur le navigateur, en dur, sans modif twig.
        return new Response ("Update performed");
    }
}
