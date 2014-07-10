<?php

namespace MewesK\WebRedirectorBundle\Twig;

use Twig_Extension;

class WebRedirectorExtension extends Twig_Extension
{
    const REGEX_PCRE = '/^(\/)(.*)(\/)(.*)$/';

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('format_regex', array($this, 'formatRegexFilter'), array('is_safe' => array('html'))),
        );
    }

    public function formatRegexFilter($string, $tag = 'span', $classes = array(
        'regex' => 'regex',
        'delimiter' => 'delimiter',
        'options' => 'options'))
    {
        if (preg_match(self::REGEX_PCRE, $string, $matches)) {
            $string = "<$tag class=\"{$classes['delimiter']}\">$matches[1]</$tag><$tag class=\"{$classes['regex']}\">$matches[2]</$tag><$tag class=\"{$classes['delimiter']}\">$matches[3]</$tag><$tag class=\"{$classes['options']}\">$matches[4]</$tag>";
        }

        return $string;
    }

    public function getName()
    {
        return 'web_redirector_extension';
    }
}