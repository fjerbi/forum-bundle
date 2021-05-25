<?php


namespace fjerbi\ForumBundle\Event;

use fjerbi\ForumBundle\Entity\Post;
use Symfony\Contracts\EventDispatcher\Event;

class PostViewEvent extends Event
{
    const VIEW = 'blog.post.view';

    private $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * @return Post
     */
    public function getPost(): Post
    {
        return $this->post;
    }

    /**
     * @param Post $post
     */
    public function setPost(Post $post): void
    {
        $this->post = $post;
    }
}