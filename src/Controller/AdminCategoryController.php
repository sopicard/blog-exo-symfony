<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Category;
use Symfony\Component\HttpFoundation\Request;

//Après avoir fait le CRUD avec ma classe category, modifications URL+name routes pour ajouter "admin"

class AdminCategoryController extends AbstractController
{
    /**
     * @Route("/admin/insert_category", name="admin_insert_category")
     */
    public function insertCategory(EntityManagerInterface $entityManager,Request $request)
    {
        //création form dans twig. Instance de classe Request + use.
        $title = $request->query->get("title");
        $color = $request->query->get("color");
        $description = $request->query->get("description");
        //condition pour que l'envoi soit validé : si titre et color ne sont pas vides (pour ne pas créer des champs vides)
        // alors { ...
        if(!empty($title) && !empty($color)) {

            $category = new Category();
            // seront affichées les valeurs du formulaire récupérées grâce à la fonction Set
            $category->setTitle($title);
            $category->setIsPublished(true);
            $category->setColor($color);
            $category->setDescription($description);

            $entityManager->persist($category);
            $entityManager->flush();
        }
        //function créer message flash (héritage AbstractController)
        $this->addFlash("success", "Votre catégorie a bien été ajoutée !");

        return $this->redirectToRoute("admin_categories");
    }

    /**
     * @Route ("/admin/category/{id}",name="admin_category")
     */
    public function showCategory($id, CategoryRepository $categoryRepository)
    {
        $category = $categoryRepository->find($id);

        return $this->render("admin/category.html.twig", ["category" => $category]);
    }

    /**
     * @Route("/admin/categories", name="admin_categories")
     */
    public function showCategories(CategoryRepository $categoriesRepository)
    {
        $categories = $categoriesRepository->findall();

        return $this->render("admin/categories.html.twig", ["categories" => $categories]);
    }

    /**
    * @Route("/admin/categories/delete/{id}", name="admin_delete_category")
    */
    public function deleteCategory($id, CategoryRepository $categoryRepository, EntityManagerInterface $entityManager)
    {
        $category = $categoryRepository->find($id);

        if(!is_null($category)) {
            $entityManager->remove($category);
            $entityManager->flush();

            $this->addFlash("success","Cet catégorie est bien supprimée !");

        }else{
            $this->addFlash("error","Cet catégorie est déjà supprimée !");

        }
        return $this->redirectToRoute("admin_categories");
    }

    /**
    * @Route("/admin/categories/update/{id}", name="admin_update_category")
    */
    public function updateCategory($id, CategoryRepository $categoryRepository, EntityManagerInterface $entityManager)
    {
        $category=$categoryRepository->find($id);

        $category->setDescription("test update");

        $entityManager->persist($category);
        $entityManager->flush();

        return new Response("Updated !");
    }
}