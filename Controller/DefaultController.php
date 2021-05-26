<?php


namespace fjerbi\ForumBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use fjerbi\ForumBundle\Entity\Category;
use fjerbi\ForumBundle\Entity\Post;
use fjerbi\ForumBundle\Entity\Question;
use fjerbi\ForumBundle\Entity\QuestionComment;
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
        $comments = $this->getDoctrine()
            ->getRepository(QuestionComment::class)->findAll();
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
            'categories'=>$categories,
            'comments'=>$comments
        ));
    }

    /**
     * @Route("/most-answered", name="forum_most_answered")
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
        $comments = $this->getDoctrine()
            ->getRepository(QuestionComment::class)->findAll();
        return $this->render('@Forum/forum/most_answered.html.twig', array(
            'questions' => $questions,
            'categories'=>$categories,
            'comments'=>$comments
        ));
    }

    /**
     * @Route("/solved", name="forum_solved_questions")
     * @param Request $request
     * @param EventDispatcherInterface|null $eventDispatcher
     * @return Response
     */
    public function SolvedAction(Request $request, EventDispatcherInterface $eventDispatcher = null)
    {
        $em = $this->getDoctrine()->getManager();
        $questions = $em->getRepository(Question::class)
            ->FindAnsweredQuestions();
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)->findAll();
        $comments = $this->getDoctrine()
            ->getRepository(QuestionComment::class)->findAll();
        return $this->render('@Forum/forum/solved.html.twig', array(
            'questions' => $questions,
            'categories'=>$categories,
            'comments'=>$comments
        ));
    }
    /**
     * @Route("/unsolved", name="forum_unsolved_questions")
     * @param Request $request
     * @param EventDispatcherInterface|null $eventDispatcher
     * @return Response
     */
    public function UnsolvedAction(Request $request, EventDispatcherInterface $eventDispatcher = null)
    {
        $em = $this->getDoctrine()->getManager();
        $questions = $em->getRepository(Question::class)
            ->FindunAnsweredQuestions();
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)->findAll();
        $comments = $this->getDoctrine()
            ->getRepository(QuestionComment::class)->findAll();
        return $this->render('@Forum/forum/unsolved.html.twig', array(
            'questions' => $questions,
            'categories'=>$categories,
            'comments'=>$comments
        ));
    }
    /**
     * @Route("/question/{id}", name="detailed_question")
     * @param Request $request
     * @param EventDispatcherInterface|null $eventDispatcher
     * @return Response
     */
    public function ShowdetailedQuestionAction($id, Question $question){
        $em = $this->getDoctrine()->getManager();
        $quest = $em->getRepository(Question::class)->find($id);
        $question->setViews($question->getViews()+1);
        $em->persist($question);
        $em->flush();
        return $this->render('@Forum/forum/detailedquestion.html.twig', array(
            'title'=>$quest->getTitle(),
            'body'=>$quest->getBody(),
            'creator'=>$quest->getCreator(),
            'category'=>$quest->getCategory(),
            'views'=>$quest->getViews(),
            'solved'=>$quest->getSolved(),
            'questions'=>$quest,
            'comments'=>$quest,
            'id'=>$id
        ));
    }
    /**
     * @Route("/comment-question", name="comment_question")
     * @param Request $request
     * @param EventDispatcherInterface|null $eventDispatcher
     * @return Response
     */
    public function addCommentAction(Request $request, UserInterface $user)
    {
        //if ($this->container->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_ANONYMOUSLY')) {
        //   return new RedirectResponse('/login');
        //}
        //$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY', null, 'unable to access this page.');

        $ref = $request->headers->get('referer');

        $question = $this->getDoctrine()
            ->getRepository(Question::class)
            ->findQuestionByid($request->request->get('question_id'));

        $comment = new QuestionComment();

        $comment->setUser($user);
        $comment->setQuestion($question);
        $comment->setComment($request->request->get('comment'));
        $comment->setCreatedAt(new \DateTime('now'));
        $em = $this->getDoctrine()->getManager();
        $em->persist($comment);
        $em->flush();

        return $this->redirect($ref);

    }

}
