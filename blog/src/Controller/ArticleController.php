<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Tests\Compiler\C;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/article", name="article")
     */
    public function index()
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        foreach ($categories as $category) {
            $articles = $this->getDoctrine()->getRepository(Article::class)->findBy(['category' => $category->getId()]);

            $articleCategory[] = $category->getArticles();
        }
        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
            'categories' => $categories,
            'articles' => $articles,
            'articleCategory'=> $articleCategory,
        ]);
    }

}
