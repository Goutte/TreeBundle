<?php

namespace Goutte\TreeBundle\Exception;

/**
 * Thrown when there is an inconsistency between the parent / children values of two nodes
 * Eg: NodeA has NodeB as child but NodeB has not NodeA as parent
 *     NodeA has not NodeB as child but NodeB has NodeA as parent
 */
class TreeIntegrityException extends \Exception
{

}