<?php
namespace AppBundle;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class BooksManager implements ContainerAwareInterface
{
    use ContainerAwareTrait;
}