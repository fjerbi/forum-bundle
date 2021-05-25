<?php


namespace fjerbi\ForumBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use fjerbi\ForumBundle\Entity\Category;
use fjerbi\ForumBundle\Entity\Post;
use fjerbi\ForumBundle\Entity\Question;
use fjerbi\ForumBundle\Event\PageViewEvent;
use fjerbi\ForumBundle\Event\PostViewEvent;
use fjerbi\ForumBundle\Form\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class DefaultController extends AbstractController
{

    /**
     * @Route("/", name="forum")
     * @param Request $request
     * @param EventDispatcherInterface|null $eventDispatcher
     * @return Response
     */
    public function indexAction(Request $request, EventDispatcherInterface $eventDispatcher = null)
    {
        $em = $this->getDoctrine()->getManager();
        $questions = $this->getDoctrine()
            ->getRepository(Question::class)->findAll();
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)->findAll();
        if ($request -> isMethod('post')){
            $search=$request->get('search');
            $questions = $em->getRepository(Question::class)
                ->findByTitle($search);
            return $this->render('@Forum/forum/index.html.twig', array('questions' => $questions,
                'categories'=>$categories));
        }
        return $this->render('@Forum/forum/index.html.twig', array(
            'questions' => $questions,
            'categories'=>$categories
        ));
    }

    /**
     * @Route("/most-response", name="forum_most_response")
     * @param Request $request
     * @param EventDispatcherInterface|null $eventDispatcher
     * @return Response
     */
    public function MostansweredAction(Request $request, EventDispatcherInterface $eventDispatcher = null)
    {
        $questions = $this->getDoctrine()
            ->getRepository(Question::class)->findAll();
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)->findAll();

        return $this->render('@Forum/forum/most_answered.html.twig', array(
            'questions' => $questions,
            'categories'=>$categories
        ));
    }

}
