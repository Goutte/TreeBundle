<?php

namespace Goutte\TreeBundle\Exception;

/**
 * Thrown when a tree has no root but we're trying to read information from it
 */
class EmptyTreeException extends \Exception
{

}