<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use Symfony\Component\HttpFoundation\Request;

//Après avoir fait le CRUD avec ma classe article, modifications URL+name routes pour ajouter "admin"

class AdminArticleController extends AbstractController
{
    // je crée une nouvelle route + méthode pour créer une nouvel article dans ma table existante article
    /**
     * @Route("/admin/insert_article", name="admin_insert_article")
     */
    public function insertArticle(EntityManagerInterface $entityManager, Request $request)
    {
        $title = $request->query->get("title");
        $content = $request->query->get("content");
        $author = $request->query->get("author");

        if(!empty($title) && !empty($content)){

            // je crée une variable dans laquelle je mets une nouvelle instance de mon entité Article
            $article = new Article();
            // et je définis les nouvelles données grâce à l'instance de classe que j'ai appelée au-dessus
            $article->setTitle($title);
            $article->setIsPublished(true);
            $article->setAuthor($author);
            $article->setContent($content);
            //ensuite je vais créer une var entityManager contenant
            // l'instance de classe EntityManagerInterface
            // le tout mis en paramètres de ma fonction insertArticle(version raccourcie du mot clé "new")

            //pour finir je pré enregistre mon nouvel article avec la fonction persist
            //et je l'envoie à la db avec la fonction flush
            $entityManager->persist($article);
            $entityManager->flush();
        }
        //function créer message flash (héritage AbstractController)
        $this->addFlash("success", "Votre article a bien été ajouté !");

        return $this->redirectToRoute("admin_articlesList");

    }
    //je crée une nouvelle route article avec une nouvelle méthode associée => afficher article en fonction id
    // (en SQL = SELECT * FROM)
    //je mets en paramètres de ma méthode une instance de classe ArticleRepository associée à var du même nom
    //ce qui me permet de me servir des propriétés de la classe repository => récupération de données
    // find => spécifique pour récup id
    /**
     * @Route("/admin/article/{id}", name="admin_article")
     */
    public function showArticle($id, ArticleRepository $articleRepository)
    {
        $article = $articleRepository->find($id);

        return $this->render("admin/article.html.twig", ["article" => $article]);
    }

    /**
     * @Route("/admin/articlesList", name="admin_articlesList")
     */
    public function showArticles(ArticleRepository $articlesRepository)
    {
        $articlesList = $articlesRepository->findall();

        return $this->render("admin/articlesList.html.twig", ["articlesList" => $articlesList]);
    }

    //méthode de suppression => mix entre Repository et Entity
    /**
     * @Route("/admin/articlesList/delete/{id}", name="admin_delete_article")
     */
    public function deleteArticle($id, ArticleRepository $articleRepository, EntityManagerInterface $entityManager)
    {
        $article=$articleRepository->find($id);
    //ajout d'une condition pour article à supprimer et pour un déjà supprimé
        if(!is_null($article)) {
            $entityManager->remove($article);
            $entityManager->flush();

            $this->addFlash("success","Cet article est bien supprimé !");
        }else{
            $this->addFlash("error","Cet article est déjà supprimé !");

        }
        return $this->redirectToRoute("admin_articlesList");
    }

    //méthode de mise à jour
    /**
     * @Route("/admin/articlesList/update/{id}", name="admin_update_article")
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
