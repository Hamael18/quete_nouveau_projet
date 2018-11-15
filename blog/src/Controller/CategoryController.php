<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/categorie/{id}", name="category_show")
     */
    public function show(Category $category) : Response
    {
        $categ = $this->getDoctrine()->getRepository(Category::class)->find($category);
        return $this->render('category/index.html.twig', [
            'categ' => $categ
        ]);
    }

}

