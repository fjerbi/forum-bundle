<?php

namespace fjerbi\ForumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="forum_questions")
 * @ORM\Entity(repositoryClass="fjerbi\ForumBundle\Repository\QuestionRepository")
 */
class Question
{

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="title",type="string")
     */
    private $title;
    /**
     * @ORM\Column(name="body",type="text")
     */
    private $body;

    /**
     * @ORM\Column(name="views",type="integer")
     */
    private $views;
    /**
     * @ORM\Column(name="solved",type="boolean")
     */
    private $solved;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="creator", referencedColumnName="id")
     */
    private $creator;
    /**
     * @ORM\OneToMany(targetEntity="fjerbi\ForumBundle\Entity\QuestionComment", mappedBy="question",cascade={"remove"}, orphanRemoval=true)
     */
    private $comments;
    /**
     * @ORM\ManyToOne(targetEntity="fjerbi\ForumBundle\Entity\Category")
     * @ORM\JoinColumn(name="category", referencedColumnName="id")
     */

    private $category;
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param mixed $body
     */
    public function setBody($body): void
    {
        $this->body = $body;
    }

    /**
     * @return mixed
     */
    public function getViews()
    {
        return $this->views;
    }

    /**
     * @param mixed $views
     */
    public function setViews($views): void
    {
        $this->views = $views;
    }

    /**
     * @return mixed
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * @param mixed $creator
     */
    public function setCreator($creator): void
    {
        $this->creator = $creator;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category): void
    {
        $this->category = $category;
    }

    /**
     * @return mixed
     */
    public function getSolved()
    {
        return $this->solved;
    }

    /**
     * @param mixed $solved
     */
    public function setSolved($solved): void
    {
        $this->solved = $solved;
    }

    /**
     * @return mixed
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param mixed $comments
     */
    public function setComments($comments): void
    {
        $this->comments = $comments;
    }


}
