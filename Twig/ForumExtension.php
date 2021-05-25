<?php

namespace fjerbi\ForumBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class ForumExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('markdown', [ForumRuntime::class, 'markdownToHTML'], ['is_safe'=> ['html']]),
        ];
    }
}