<?php

namespace Goutte\TreeBundle\Is;

/**
 * This is the base interface for a Rooted Tree
 * It follows the Composite structural pattern, and may either :
 * - delegate all of its Node methods to its root node (trait ComposeWithRootNode)
 * - throw on any Node method (trait ForbidNodeComposition)
 *
 */
interface Tree extends Node {}