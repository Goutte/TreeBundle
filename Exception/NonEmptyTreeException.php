<?php

namespace Goutte\TreeBundle\Exception;

/**
 * Thrown when a Tree already has a root but we're trying to set a new one
 */
class NonEmptyTreeException extends \Exception
{

}