<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Tests\Compiler\C;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use App\Entity\Category;
use Symfony\Component\HttpFoundation\Response;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog/{page}", requirements={"page"="\d+"}, name="blog_index")
     */
    public function list($page)
    {
        return $this->render('blog/index.html.twig', ['page' => $page]);
    }
    /**
     * Getting a article with a formatted slug for title
     *
     * @param string $slug The slugger
     *
     * @Route("/{slug<^[a-z0-9-]+$>}",
     *     name="blog_show")
     *  @return Response A response instance
     */
    public function show($slug) : Response
    {
        if (!$slug) {
            throw $this
                ->createNotFoundException('No slug has been sent to find an article in article\'s table.');
        }

        $slug = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($slug)), "-")
        );

        $article = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findOneBy(['title' => mb_strtolower($slug)]);

        if (!$article) {
            throw $this->createNotFoundException(
                'No article with '.$slug.' title, found in article\'s table.'
            );
        }

        return $this->render(
            'blog/show.html.twig',
            [
                'article' => $article,
                'slug'=>$slug
            ]
        );
    }
    /**
     * Show all row from article's entity
     *
     * @Route("/", name="blog_index")
     * @return Response A response instance
     */
    public function index() : Response
    {
        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findAll();

        if (!$articles) {
            throw $this->createNotFoundException(
                'No article found in article\'s table.'
            );
        }

        return $this->render(
            'blog/index.html.twig',
            ['articles' => $articles]
        );
    }
    /**
     * Show all row from category
     *
     * @Route("/category/{name}", name="blog_show_category")
     * @return Response A response instance
     */
    public function showByCategory(Category $category)
    {
        $categories=$this->getDoctrine()->getRepository(Category::class)->find($category);
        $articlesCategory=$this->getDoctrine()
            ->getRepository(Article::class)
            ->findBycategory($category,['id'=>'DESC'],3);
        if (!$articlesCategory) {
            throw $this->createNotFoundException(
                'No article found in this category'
            );
        }

        return $this->render(
            'blog/category.html.twig',
            ['articlesCategory' => $articlesCategory,'categories'=>$categories]
        );
    }


}
