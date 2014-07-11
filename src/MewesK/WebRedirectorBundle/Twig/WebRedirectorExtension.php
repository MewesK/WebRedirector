<?php

namespace MewesK\WebRedirectorBundle\Twig;

use Twig_Extension;

class WebRedirectorExtension extends Twig_Extension
{
    const REGEX_PCRE = '/^(\/)(.*)(\/)(.*)$/';
    const REGEX_DESTINATION = '/^(.+:\/\/)([^\/]+)(\/?[^\?]*)?(\??.*)?$/';

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('format_regex', array($this, 'formatRegexFilter'), array('is_safe' => array('html'))),
            new \Twig_SimpleFilter('format_destination', array($this, 'formatDestinationFilter'), array('is_safe' => array('html')))
        );
    }

    public function formatRegexFilter($string, $tag = 'span', $classes = array(
        'regex' => array('class' => 'regex', 'title' => 'Regular Expression'),
        'delimiter' => array('class' => 'delimiter', 'title' => 'Delimiter'),
        'options' => array('class' => 'options', 'title' => "Options\ni = case insensitive\nm = multi line\ns = single line\nx = extended")))
    {
        if (preg_match(self::REGEX_PCRE, $string, $matches)) {
            $string =
                "<$tag class=\"{$classes['delimiter']['class']}\" title=\"{$classes['delimiter']['title']}\">$matches[1]</$tag>" .
                "<$tag class=\"{$classes['regex']['class']}\" title=\"{$classes['regex']['title']}\">$matches[2]</$tag>" .
                "<$tag class=\"{$classes['delimiter']['class']}\" title=\"{$classes['delimiter']['title']}\">$matches[3]</$tag>" .
                "<$tag class=\"{$classes['options']['class']}\" title=\"{$classes['options']['title']}\">$matches[4]</$tag>";
        }

        return $string;
    }

    public function formatDestinationFilter($string, $tag = 'span', $classes = array(
        'scheme' => array('class' => 'scheme', 'title' => 'Scheme'),
        'hostname' => array('class' => 'hostname', 'title' => 'Hostname'),
        'path' => array('class' => 'path', 'title' => 'Path'),
        'query' => array('class' => 'query', 'title' => 'Query')))
    {
        if (preg_match(self::REGEX_DESTINATION, $string, $matches)) {
            $string =
                "<$tag class=\"{$classes['scheme']['class']}\" title=\"{$classes['scheme']['title']}\">$matches[1]</$tag>" .
                "<$tag class=\"{$classes['hostname']['class']}\" title=\"{$classes['hostname']['title']}\">$matches[2]</$tag>" .
                "<$tag class=\"{$classes['path']['class']}\" title=\"{$classes['path']['title']}\">$matches[3]</$tag>" .
                "<$tag class=\"{$classes['query']['class']}\" title=\"{$classes['query']['title']}\">$matches[4]</$tag>";
        }

        return $string;
    }

    public function getName()
    {
        return 'web_redirector_extension';
    }
}