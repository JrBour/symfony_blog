<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\CategoryRepository;
use App\Repository\BlogRepository;
use Symfony\Component\HttpFoundation\Response;
use App\Form\CategoryType;
use App\Entity\Category;
use App\Entity\Blog;
use App\Entity\User;

class CategoryController extends Controller
{
    /**
     * @Route("/category", name="category")
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('category/index.html.twig',['categories' => $categoryRepository->findAll()]);
    }

    /**
    * @Route("/category/post/{id}", name="category_show_post")
    **/
    public function showPostByCategory(int $id , CategoryRepository $categoryRepository, BlogRepository $blogRepository): Response
    {
        return $this->render('category/post_category.html.twig',[
            'posts' => $blogRepository->findByCategory($id),
            'category' => $categoryRepository->find($id)
        ]);
    }

    /**
    * @Route("/category/add", name="category_post")
    **/
    public function post(Request $request): Response
    {
      $em = $this->getDoctrine()->getManager();
      $category = new Category();
      $form = $this->createForm(CategoryType::class, $category);
      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
        $category = $form->getData();
        $file = $category->getImage();
        $user = $this->getUser();
        $fileName = md5(uniqid()) . '.' . $file->guessExtension();
        $file->move(
          $this->getParameter('images'),
          $fileName
        );
        $name = "/images/posts/" . $fileName;
        $category->setImage($name);
        $category->setName($category->getName());
        $category->setAuthor($user);
        $em->persist($category);
        $em->flush();

        return $this->redirectToRoute('category');
      }

      return $this->render('category/add.html.twig', ['form' => $form->createView()]);
    }

    /**
    * @Route("/category/edit/{id}", name="category_edit")
    **/
    public function edit(Request $request, int $id, CategoryRepository $categoryRepository): Response
    {
      $em = $this->getDoctrine()->getManager();
      $category = $categoryRepository->find($id);
      $form = $this->createForm(CategoryType::class, $category);
      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
        $category = $form->getData();
        $file = $category->getImage();
        $fileName = md5(uniqid()) . '.' . $file->guessExtension();
        $file->move(
          $this->getParameter('images'),
          $fileName
        );
        $name = "/images/posts/" . $fileName;
        $category->setImage($name);
        $category->setName($category->getName());
        $em->flush();

        return $this->redirectToRoute('category');
      }

      return $this->render('category/add.html.twig', ['form' => $form->createView()]);
    }

    /**
    * @Route("/category/remove/{id}", name="category_remove")
    **/
    public function delete(int $id): Response
    {
      $em = $this->getDoctrine()->getManager();
      $category = $em->getRepository(Category::class)->find($id);

      if (!$category) {
        throw $this->createNotFoundException(
          'Pas de catégorie correspondant à l\'id n° : ' . $id
        );
      }
      $em->remove($category);
      $em->flush();

      return $this->redirectToRoute('category');
    }
}
