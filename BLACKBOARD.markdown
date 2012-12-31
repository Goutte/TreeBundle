BLACKBOARD
==========

Some graph theory thoughts for refactorization, but I'm getting somewhat confused as to how to implement them practically.

These belong to a GraphTheoryBundle, so that I can keep the current very simple Node implementation in this bundle.


Graph
-----

    Graph < ConnectedGraph < Tree
    Graph < ConnectedGraph < DirectedGraph < RootedTree

Graph (has Vertices)
  - getVertices

ConnectedGraph (has Edges)
  - getLeaves
  - getVerticesAlongPathTo(Vertex)

Tree (has NoSimpleCycle)

DirectedGraph (has Arcs)
  - getSources
  - getSinks

RootedTree (has NoSimpleCycle)
  - getRoot (single source)
  - getLeaves = getSinks


Edge
----

Edge < Arc

Edge (has Vertices)
  - getEndpoints (exactly 2)

Arc (has Direction)
  - getHead (child)
  - getTail (parent)

=> `/!\` confusing !

also: has Weight, haz CheeseBurger


Vertex
------

    Vertex < ConnectedVertex < TreeNode
    Vertex < ConnectedVertex < DirectedVertex < RootedTreeNode

Vertex (has Label, has Graph)
  - get/set Graph
  - get/set Label

ConnectedVertex (has Edges)
  - use Edges
  - getAdjacent
  - countAdjacent = countEdges

DirectedVertex (has Arcs)
  - getArcs = getEdges
  - add/remove Arc
  - getPredecessors
  - getDirectPredecessors
  - getSuccessors
  - getDirectSuccessors

TreeNode (has NoSimpleCycle)

RootedTreeNode (has NoSimpleCycle, has SingleDirectPredecessor or SingleParent)
  - use FamilyNotation
  - use SingleParent
  - use IsNotPartOfASimpleCycle


Traits
------

### Vertex

IsNotPartOfASimpleCycle
  - addEdge (override and throw when cycle is detected)

Edges
  - getEdges
  - add/remove Edge

FamilyNotation
  - getChildren (direct successors)
  - add/remove Child
  - getNthChild
  - get/set Parent (single direct predecessor)
  - getAncestors
  - getDescendants

SingleParent
  - addParent (override and keep unicity)
  - setParent

SingleChild
  - addChild (override and keep unicity)
  - getChild




Rooted Tree Nodes
-----------------

UnaryOperator (has SingleChild or SingleDirectSuccessor)
  - use SingleChild

BinaryOperator (has TwoChildren or TwoDirectSuccessors)

TernaryOperator (has ThreeChildren or ThreeDirectSuccessors)

Operand (has NoChild or NoDirectSuccessor)