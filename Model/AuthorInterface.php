<?php



namespace fjerbi\ForumBundle\Model;


interface AuthorInterface
{
    /**
     * Get the unique identifier
     *
     * @return int
     */
    public function getId(): ?int;

    /**
     * Get the username
     *
     * @return string
     */
    public function getUsername(): ?string;

    /**
     * Get the first name
     *
     * @return string
     */
    public function getFirstName(): ?string;

    /**
     * Get the full name
     *
     * @return string
     */
    public function getLastName(): ?string;
}