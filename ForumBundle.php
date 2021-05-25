<?php



namespace fjerbi\ForumBundle;

use fjerbi\ForumBundle\DependencyInjection\fjerbiForumExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;


class ForumBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new fjerbiForumExtension();
    }
}
