<?php

namespace App\Controller;

use App\Form\ArticleType;
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
        // 2- version "magique" de création de formulaire Symfony :
        //création instance classe Article pour créer nouvel article dans db
        $article = new Article ();

        //Grâce à la classe ArticleType (crée via ligne commande) j'ai un "patron" de formulaire
        // qui me sert de modèle pour créer les articles. Ensuite en utilisant la méthode createForm,
        //je crée le formulaire en utilisant instance de classe ArticleType qui est mon modèle de base
        $form = $this->createForm(ArticleType::class, $article);

        //on donne à la var form (qui contient formulaire) une instance de la classe request (déclarée en param de ma fonction au-dessus)
        //afin que le formulaire puisse récup ttes données entrées dans les inputs. les set sur $article
        //se font auto grâce à new Article au-dessus.
        $form->handleRequest($request);

        // si form posté && données valides (ex: pas de caractères type string dans date)
        if($form->isSubmitted() && $form->isValid()){
            //pré enregistrement et envoi pour enregistrement ok
            $entityManager->persist($article);
            $entityManager->flush();

            $this->addFlash("success", " Article enregistré ! ");
        }

        //j'affiche le twig (créer au préalable en version insert) avec la variable form qui contient la vue du formulaire
        return $this->render("admin/insert_article.html.twig", ["form"=>$form->createView()]);

    }
          // 1- version de création de formulaire "à la main" dans la public function ci-dessus :
//        $title = $request->query->get("title");
//        $content = $request->query->get("content");
//        $author = $request->query->get("author");
//
//        if(!empty($title) && !empty($content)){
//
//            // je crée une variable dans laquelle je mets une nouvelle instance de mon entité Article
//            $article = new Article();
//            // et je définis les nouvelles données grâce à l'instance de classe que j'ai appelée au-dessus
//            $article->setTitle($title);
//            $article->setIsPublished(true);
//            $article->setAuthor($author);
//            $article->setContent($content);
//            //ensuite je vais créer une var entityManager contenant
//            // l'instance de classe EntityManagerInterface
//            // le tout mis en paramètres de ma fonction insertArticle(version raccourcie du mot clé "new")
//
//            //pour finir je pré enregistre mon nouvel article avec la fonction persist
//            //et je l'envoie à la db avec la fonction flush
//            $entityManager->persist($article);
//            $entityManager->flush();
//        }
//        //function créer message flash (héritage AbstractController)
//        $this->addFlash("success", "Votre article a bien été ajouté !");
//
//        return $this->redirectToRoute("admin_articlesList");
//
//    }
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
    public function updateArticle($id, ArticleRepository $articleRepository, EntityManagerInterface $entityManager, Request $request)
    {
        $article = $articleRepository->find($id);

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($article);
            $entityManager->flush();

            $this->addFlash("success", " Article modifié ! ");
        }

        return $this->render("admin/update_article.html.twig", [
            "form" => $form->createView()
        ]);
    }
//        //Modif des données en dur : exercice initial
//
//        //les valeurs à modifier
//        $article->setTitle("title updated");
//        $article->setContent("test update");
//
//        $entityManager->persist($article);
//        $entityManager->flush();
//        //la réponse affichée sur le navigateur, en dur, sans modif twig.
//        return new Response ("Update performed");

        //1 AdCo = rte +fct° searchArticle
        //2 ArRe = rte + fct° pour définir searchArticle
        //3 basetwig = créa form
        //4 AdCo = instance request (+test dd $search) + instance Repository
        //5 ArRe = (test dd "methode depuis le repo" avec search?search=fruit dans URL)
        //6 ArRe = définir fct° search Article avec QueryBuilder
        //7 AdCo = renvoyer vers fichier twig (return)
        //8 Création fichier twig search_articles.html.twig

        //-> Création nelle route + fonction (testées avec dd)
        /**
         * @Route("/admin/articles/search", name="admin_search_articles")
         */
      public function searchArticles(Request $request, ArticleRepository $articleRepository)
    {
        //instance classe Request permet récup données dans URL (ma route)
        $search = $request->query->get("search");

        //instance classe Repository permet de faire le SELECT du SQL
        //création d'une méthode dans ArticleRepository pour trouver un article en fonction d'1 word ($search) dans son titre
        $articles = $articleRepository->searchByWord($search);

        //->renvoyer un fichier twig où il y aura tous les articles trouvés et les afficher

        return $this->render("admin/search_articles.html.twig", [
            "articles"=> $articles
        ]);
    }
}

