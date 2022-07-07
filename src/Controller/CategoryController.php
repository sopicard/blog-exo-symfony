<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Category;

class CategoryController extends AbstractController
{
    /**
     * @Route("insert-category", name="insert_category")
     */
    public function insertCategory(EntityManagerInterface $entityManager)
    {
        $category = new Category();

        $category->setTitle("New title");
        $category->setIsPublished(true);
        $category->setColor("New color");
        $category->setDescription("Lorem ipsum");

        $entityManager->persist($category);
        $entityManager->flush();

        dd($category);
    }
    /**
     * @Route ("category",name="category")
     */
    public function showCategory(CategoryRepository $categoryRepository)
    {
        $category = $categoryRepository->find(1);

        dd($category);
    }

    /**
     * @Route("categories", name="categories")
     */
    public function showCategories(CategoryRepository $categoriesRepository)
    {
        $categories = $categoriesRepository->findall();

        dd($categories);
    }
}