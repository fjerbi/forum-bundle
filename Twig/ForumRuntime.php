<?php

namespace fjerbi\ForumBundle\Twig;

use Parsedown;
use Twig\Extension\RuntimeExtensionInterface;

class ForumRuntime implements RuntimeExtensionInterface
{
    public function __construct()
    {
    }

    public function markdownToHTML($text)
    {
        $parsedown = new Parsedown();
        return $parsedown->parse($text);
    }
}