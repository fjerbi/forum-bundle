<?php


namespace fjerbi\ForumBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * QuestionRepository
 *
 */
class QuestionRepository extends EntityRepository
{

    public function findByTagSlug(string $slug, int $limit,int $offset): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT bp FROM fjerbi\ForumBundle\Entity\Question bp
            JOIN bp.tags pt
            WHERE pt.slug = :slug
            ORDER BY bp.created DESC'
        )->setParameter('slug', $slug)
            ->setMaxResults($limit)
            ->setFirstResult($offset);

        return $query->getResult();
    }
    public function findByTitle($motcle){
    $query=$this->getEntityManager()
        ->createQuery("
            select e from fjerbi\ForumBundle\Entity\Question e
            where e.title like :motcle")
        ->setParameter('motcle',$motcle.'%');
    return $query->getResult();
}

}
