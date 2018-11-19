<?php

namespace App\Controller;

use App\Form\ArticleType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Tests\Compiler\C;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use App\Entity\Category;
use Symfony\Component\HttpFoundation\Response;
use App\Form\ArticleSearchType;
use App\Form\CategoryType;
use App\Entity\Tag;

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
     * @Route("/blog/{title}", name="article_show")
     */
    public function showOne(Article $article)
    {

        return $this->render('blog/article.html.twig', ['article'=>$article]);
    }

    /**
     * Show all row from article's entity
     *
     * @Route("/", name="blog_index")
     * @return Response A response instance
     */
    public function index(Request $request, ObjectManager $manager) : Response
    {
        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findAll();

        if (!$articles) {
            throw $this->createNotFoundException(
                'No article found in article\'s table.'
            );
        }

        $form = $this->createForm(ArticleSearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted())
        {
            $recherche= $form->getData();
            $article=$this->getDoctrine()->getRepository(Article::class)->findBy(
                ['title'=>$recherche]
            );
            return $this->redirectToRoute('article_show',['title'=>$recherche['searchField']]);

        }
        $articleAjout= new Article();
        $form2= $this->createForm(ArticleType::class, $articleAjout);
        $form2->handleRequest($request);
        if ($form2->isSubmitted())
        {
            $manager->persist($articleAjout);
            $manager->flush();

            return $this->redirectToRoute('blog_index');
        }

        return $this->render(
            'blog/index.html.twig', [
                'articles' => $articles,
                'form' => $form->createView(),
                'form2'=> $form2->createView(),
            ]
        );

    }
    /**
     * @Route("/category", name="category")
     */
    public function indexCategory(Request $request, ObjectManager $manager) : Response
    {
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();
        if (!$categories) {
            throw $this->createNotFoundException(
                'No category\'s found in category\'s table.'
            );
        }
        $category= new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted())
        {
            $manager->persist($category);
            $manager->flush();

            return $this->redirectToRoute('category');

        }

        return $this->render(
            'category/index.html.twig', [
                'categories' => $categories,
                'form' => $form->createView(),
            ]
        );
    }
    /**
     * @Route("/category/{id}", name="category_show")
     */
    public function showCategory(Category $category) : Response
    {
        $categ = $this->getDoctrine()->getRepository(Category::class)->find($category);
        return $this->render('category/index.html.twig', [
            'categ' => $categ
        ]);
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
     * @param Tag $tag
     * @return Response
     * @Route("tag/{name}", name="blog_show_tag")
     */
    public function showByTag (Tag $tag)
    {
        return $this->render('blog/tags.html.twig', ['tag'=>$tag]);
    }
}
