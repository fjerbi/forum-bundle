<?php


namespace fjerbi\ForumBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use fjerbi\ForumBundle\Entity\Post;
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
        $page = $request->query->get('page', 1);
        $em = $this->getDoctrine()->getManager();
        $posts = $this->getDoctrine()
            ->getRepository(Post::class)
            ->findBy(["isPublished" => true], ["created" => "desc"]);

        if ($eventDispatcher) {
            $eventDispatcher->dispatch(new PageViewEvent($page));
        }

        $hasNext = $this->getDoctrine()
            ->getRepository(Post::class)
            ->findBy(["isPublished" => true], ["created" => "desc"]);
        if ($request -> isMethod('post')){
            $search=$request->get('search');
            $posts = $em->getRepository(Post::class)
                ->findByTitle($search);
            return $this->render('@Blog/blog/index.html.twig', array('posts' => $posts));
        }
        return $this->render('@Blog/blog/index.html.twig', [
            'page' => $page,
            'posts' => $posts,
            'previous_page' => $page != 1 ? $this->generateUrl('forum', ["page" => $page - 1]) : null,
            'next_page' => count($hasNext) > 0 ? $this->generateUrl('forum', ["page" => $page + 1]) : null,
        ]);
    }
     /**
      * @Route("/manage", name="blog_manage")
      * @param Request $request
      * @param EventDispatcherInterface|null $eventDispatcher
      * @return Response
      * @throws Exception
      */
     public function manageAction(Request $request, ?EventDispatcherInterface $eventDispatcher)
     {
         $page = $request->query->get('page', 1);

         $posts = $this->getDoctrine()
             ->getRepository(Post::class)
             ->findBy([], ["created" => "desc"], 10, ($page - 1) * 10);

         $nextPosts = $this->getDoctrine()
             ->getRepository(Post::class)
             ->findBy([], ["created" => "desc"], 10, $page * 10);

         return $this->render('@Blog/blog/manage.html.twig', [
             'page' => $page,
             'posts' => $posts,
             'previous_page' => $page != 1 ? $this->generateUrl('blog_manage', ["page" => $page - 1]) : null,
             'next_page' => count($nextPosts) > 0 ? $this->generateUrl('blog_manage', ["page" => $page + 1]) : null,
         ]);
     }

     /**
      * @Route("/{id}-{slug}", name="blog_post", requirements={"id"="\d+"})
      * @param Request $request
      * @param EventDispatcherInterface|null $eventDispatcher
      * @param $id
      * @param $slug
      * @return Response
      */
     public function ViewPostAction(Request $request, $id, $slug, EventDispatcherInterface $eventDispatcher)
     {

         $post = $this->getDoctrine()
             ->getRepository(Post::class)
             ->findOneBy(["id" => $id, "slug" => $slug]);

         if ($post instanceof Post && $eventDispatcher) {
             $eventDispatcher->dispatch(new PostViewEvent($post));
         }

         return $this->render('@Blog/blog/index.html.twig', [
             'page' => $id,
             'posts' => [$post],
         ]);
     }

     /**
      * @Route("/edit/{id}", name="blog_edit", requirements={"id"="\d+"}, methods={"GET","POST"})
      * @param Request $request
      * @param EventDispatcherInterface $eventDispatcher
      * @param $id
      * @return RedirectResponse|Response
      * @throws Exception
      * @throws NotFoundHttpException
      */
     public function editAction(Request $request, EventDispatcherInterface $eventDispatcher, $id)
     {
         $post = $this->getDoctrine()
             ->getRepository(Post::class)
             ->findOneBy(["id" => $id]);

         if (!$post instanceof Post) {
             throw $this->createNotFoundException("Could not find post for id " . $id);
         }

         $form = $this->createForm(PostType::class, $post, [
             'submit_label' => 'Save',
             'publish_label' => ($post->isPublished()) ? 'Save & Hide' : 'Save & Publish',
             'entity_manager' => $this->getDoctrine()->getManager()
         ]);

         $originalTags = new ArrayCollection();

         foreach ($post->getTags() as $tag) {
             $originalTags->add($tag);
         }

         $form->handleRequest($request);

         if ($form->isSubmitted() && $form->isValid()) {
             $entityManager = $this->getDoctrine()->getManager();
             $message = "Successfully edited.";
             $type = "success";

             if ($form->get('publish')->isClicked()) {
                 $post->setPublished(($post->isPublished()) ? false : true);
                 $entityManager->persist($post);
                 $entityManager->flush();
                 $message = ($post->isPublished()) ? "Successfully published." : "Successfully hidden.";
             } else if ($form->get('delete')->isClicked()) {
                 return $this->redirectToRoute("blog_delete", ["id" => $post->getId()]);
             }

             $entityManager->persist($post);
             $entityManager->flush();

             $this->addFlash(
                 $type,
                 $message
             );

             return $this->redirectToRoute("blog_edit", ["id" => $id]);
         }

         return $this->render('@Blog/blog/editor.html.twig', [
             'form' => $form->createView()
         ]);
     }

     /**
      * @Route("/tag/{slug}", name="blog_post_tag")
      * @param Request $request
      * @param $slug
      * @return RedirectResponse|Response
      * @throws Exception
      * @throws NotFoundHttpException
      */
     public function TagAction(Request $request, string $slug)
     {
         $page = $request->query->get('page', 1);

         $posts = $this->getDoctrine()
             ->getRepository(Post::class)
             ->findByTagSlug($slug, 3, ($page - 1) * 3);

         $hasNext = $this->getDoctrine()
             ->getRepository(Post::class)
             ->findByTagSlug($slug, 3, $page * 3);

         return $this->render('@Blog/blog/index.html.twig', [
             'page' => $page,
             'posts' => $posts,
             'previous_page' => $page != 1 ? $this->generateUrl('blog', ["page" => $page - 1]) : null,
             'next_page' => count($hasNext) > 0 ? $this->generateUrl('blog', ["page" => $page + 1]) : null,
         ]);
     }


    /**
     * @Route("/create", name="blog_create")
     * @param Request $request
     * @param EventDispatcherInterface|null $eventDispatcher
     * @return Response
     * @throws Exception
     */
    public function CreateAction(Request $request, EventDispatcherInterface $eventDispatcher)
    {
        $post = new Post();
        $author = $this->getUser();
        if ($author instanceof UserInterface) {
            $post->setAuthor($author);
        }

        $form = $this->createForm(PostType::class, $post, [
            'submit_label' => 'Publish',
            'entity_manager'=>$this->getDoctrine()->getManager()
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $message = "Successfully published.";
            $type = "success";

            $entityManager->persist($post);
            $entityManager->flush();

            $this->addFlash(
                $type,
                $message
            );

            return $this->redirectToRoute("blog_edit", ["id" => $post->getId()]);
        }

        return $this->render('@Blog/blog/editor.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/delete/{id}", name="blog_delete", requirements={"id"="\d+"})
     * @param Request $request
     * @param EventDispatcherInterface|null $eventDispatcher
     * @param int $id
     * @return RedirectResponse
     */
    public function DeleteAction(Request $request, ?EventDispatcherInterface $eventDispatcher, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $post = $this->getDoctrine()
            ->getRepository(Post::class)
            ->find($id);

        if (!$post instanceof Post) {
            throw $this->createNotFoundException("Could not find post for id " . $id);
        }

        $em->remove($post);
        $em->flush();

        $this->addFlash(
            'success',
            'Successfully deleted.'
        );

        return $this->redirectToRoute('blog_manage');
    }
}
