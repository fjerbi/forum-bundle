<?php
namespace fjerbi\ForumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * QuestionComment
 * @ORM\Entity
 * @ORM\Table(name="question_comments")

 */
class QuestionComment
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="comment",type="text")
     */
    private $comment;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="user", referencedColumnName="id")
     */

    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="fjerbi\ForumBundle\Entity\Question")
     * @ORM\JoinColumn(name="question", referencedColumnName="id")
     */

    private $question;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="date")
     */
    private $createdAt;

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
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param mixed $comment
     */
    public function setComment($comment): void
    {
        $this->comment = $comment;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * @param mixed $question
     */
    public function setQuestion($question): void
    {
        $this->question = $question;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }


}