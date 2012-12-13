<?php

namespace Goutte\TreeBundle\Exception;

/**
 * Thrown when a mutator tries to create a cycle in the rooted tree
 */
class CyclicReferenceException extends \Exception
{

}