<?php


namespace fjerbi\ForumBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * PostRepository
 *
 */
class PostRepository extends EntityRepository
{

    public function findByTagSlug(string $slug, int $limit,int $offset): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT bp FROM fjerbi\BlogBundle\Entity\Post bp
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
            select e from fjerbi\BlogBundle\Entity\Post e
            where e.title like :motcle")
        ->setParameter('motcle',$motcle.'%');
    return $query->getResult();
}

}
