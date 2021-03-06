<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\User;
use App\Entity\Category;
use App\Entity\Blog;

class DefaultController extends Controller
{
    /**
     * Render the homepage view with the three last articles and categories create
     * @param TranslatorInterface   $translator   Translate word in english/french
     * @return Response     The response send a view in twig
     * @Route("/", name="home")
     */
    public function home(TranslatorInterface $translator): Response
    {
        $posts = $this->getDoctrine()->getRepository(Blog::class)->findByThreeLast();
        $categories = $this->getDoctrine()->getRepository(Category::class)->findByThreeLast();
        $user = $this->getUser();
        $serial =  ($user) ? $user->serialize() : 'Not connect';

        return $this->render('home.html.twig', [
            'posts' => $posts,
            'categories' => $categories,
            'serial' => $serial
        ]);
    }

    /**
     * Render the view with the profil of the current user
     * @return Response     The response send a view in twig
     * @Route("/profil", name="profil")
     */
    public function profil()
    {
        $user = $this->getUser();
        $id = $user->getId();
        $posts = $this->getDoctrine()->getRepository(Blog::class)->findByAuthor($id);
        $categories = $this->getDoctrine()->getRepository(Category::class)->findByAuthor($id);

        $newUser = $this->getDoctrine()->getRepository(User::class)->find($id);
        $followers = $newUser->getFollowing();

        return $this->render('login/profile.html.twig', [
            'posts' => $posts,
            'followers' => $followers,
            'categories' => $categories
        ]);
    }

    /**
     * Edit the local store in session
     * @param       Request         $request        The request send by the ajax method
     * @return \Symfony\Component\HttpFoundation\JsonResponse       Confirm the success of the operation
     * @Route("/locale", name="local_edit")
     */
    public function editLocale(Request $request)
    {
        if($request->isXmlHttpRequest()) {
            $data = $request->request->all();
            $request->getSession()->set('_locale', $data['local']);

            return $this->json(['success' => 'Le local a bien était modifié !'], 200);
        }

        return $this->json(['error' => 'Une erreur est survenu'], 400);
    }

    /**
     * Display the profil of an user
     * @param  int     $id     The user id
     * @return Response       The response send a view in twig
     * @Route("/profil/{id}", name="profil_user")
     */
    public function profileUser(int $id)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        $posts = $this->getDoctrine()->getRepository(Blog::class)->findByAuthor($id);
        $categories = $this->getDoctrine()->getRepository(Category::class)->findByAuthor($id);
        $checkFollowing = $this->getUser()->hasFollowing($user);


        return $this->render('login/profile_user.html.twig', [
            'user' => $user,
            'posts' => $posts,
            'categories' => $categories,
            'follow' => $checkFollowing
        ]);
    }

    /**
     * Get the user informations by id
     * @param       Request         $request        The request send to the controller
     * @return JsonResponse         The response in json
     * @Route("/user/info", name="user_info")
     */
    public function userInformations(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($data['id']);

        if ($user) {

            return $this->json(['user' => $user->getUsername()], 200);
        }

        return $this->json(['error' => 'Id incorrect'], 400);

    }

    /**
     * Add a new user to follow for the current user
     * @param       Request         $request        The ajax request
     * @return      Response        The json response
     * @Route("/follow", name="follow")
     */
    public function followUser(Request $request): Response
    {
        if ($request->isXmlHttpRequest()) {
            $data = json_decode($request->getContent(),true);
            $em = $this->getDoctrine()->getManager();
            $following = $em->getRepository(User::class)->find($data['id']);

            if ($following) {
                $follower = $this->getUser();
                $follower->setFollowing($following);
                $following->setFollower($this->getUser());
                $em->persist($this->getUser());
                $em->persist($following);
                $em->flush();

                return $this->json(['success' => "Vous suivez désormais " . $following->getUsername()], 201);
            }

            return $this->json(['error' => 'L\'utilisateur n\'a pas pu être trouvé'], 400);
        }
    }


    /**
     * Unfollow an user. Delete the relationship in database
     * @param       Request         $request        The ajax request send by the view
     * @return JsonResponse
     * @Route("/unfollow", name="unfollow")
     */
    public function unfollowUser(Request $request): Response
    {
        if ($request->isXmlHttpRequest()) {
            $data = $request->request->all();
            $em = $this->getDoctrine()->getManager();
            $following = $em->getRepository(User::class)->find($data['id']);

            if ($following) {
                $follower = $this->getUser();
                $following->removeUser($follower);
                $follower->removeUser($following);
                $em->flush();

                return $this->json(['success' => 'Vous ne suivez plus '.$following->getUsername()], 200);
            }

            return $this->json(['error' => 'Une erreur s\'est produite'], 400);
        }
    }
}

?>
