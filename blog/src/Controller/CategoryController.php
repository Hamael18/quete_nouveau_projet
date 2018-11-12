<?php

namespace App\Controller;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/categ/{id}", name="category")
     */
    public function show(Category $category) : Response
    {
        $categ = $this->getDoctrine()->getRepository(Category::class)->find($category);
        return $this->render('category/index.html.twig', [
            'categ' => $categ
        ]);
    }
}
