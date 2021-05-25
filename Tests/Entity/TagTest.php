<?php


namespace Tests\BlogBundle\Entity;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use PHPUnit\Framework\TestCase;
use BlogBundle\Entity\Tag;


class TagTest extends TestCase {
    public function testPostInteraction() {
        
        $expectedTagValues = ["name" => "Sample Category", "slug"=>"category-sample"];
        $category = new Tag($expectedTagValues["name"], $expectedTagValues["slug"]);
        
        $categoryRepository = $this->createMock(ObjectRepository::class);
        $categoryRepository->expects($this->any())
            ->method('find')
            ->willReturn($expectedTagValues);

        $objectManager = $this->createMock(ObjectManager::class);
        $objectManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($category);
        
        $this->assertEquals($expectedTagValues["name"], $category->getName());
        $this->assertEquals($expectedTagValues["slug"], $category->getSlug());
    }
}