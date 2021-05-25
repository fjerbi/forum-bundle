<?php


namespace Tests\BlogBundle\Entity;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use PHPUnit\Framework\TestCase;
use BlogBundle\Entity\Category;
use BlogBundle\Repository\CategoryRepository;


class CategoryTest extends TestCase {
    public function testPostInteraction() {
        
        $expectedCategoryValues = ["name" => "Sample Category", "slug"=>"category-sample"];
        $category = new Category($expectedCategoryValues["name"], $expectedCategoryValues["slug"]);
        
        $categoryRepository = $this->createMock(ObjectRepository::class);
        $categoryRepository->expects($this->any())
            ->method('find')
            ->willReturn($expectedCategoryValues);
        
        $objectManager = $this->createMock(ObjectManager::class);
        $objectManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($category);
        
        $this->assertEquals($expectedCategoryValues["name"], $category->getName());
        $this->assertEquals($expectedCategoryValues["slug"], $category->getSlug());
    }
}