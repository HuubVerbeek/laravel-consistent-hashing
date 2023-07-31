# Laravel Consistent Hashing

Laravel Consistent Hashing is a package that provides a consistent hashing service to distribute data across multiple nodes while maintaining data stability and minimizing data migration when nodes are added or removed from the system.

## Installation

To get started with Laravel Consistent Hashing, follow these installation steps:

1. Install the package using Composer:

```bash
composer require huubverbeek/laravel-consistent-hashing
```

2. Publish the configuration file:

```bash
php artisan vendor:publish --provider="HuubVerbeek\ConsistentHashing\ConsistentHashingServiceProvider" --tag="config"
```

3. Configure the package by editing the `config/consistent-hashing.php` file. You can define the implementation classes for the getter and setter contracts, as well as the strategy for rekeying the nodes when a new node is added or removed.

## Usage

### `NodeCollection`

This abstract class provides methods for managing a collection of nodes and computing distances between nodes based on input degree.

```php
<?php

namespace HuubVerbeek\ConsistentHashing;

abstract class NodeCollection extends Collection
{
    /**
     * @param string $identifier
     * @return AbstractNode|null
     */
    public function findByIdentifier(string $identifier): ?AbstractNode;

    /**
     * @param string $identifier
     * @return bool
     */
    public function remove(string $identifier): bool;

    /**
     * @param int $degree
     * @return AbstractNode|null
     */
    public function next(int $degree): ?AbstractNode;

    /**
     * @param int $degree
     * @return AbstractNode|null
     */
    public function previous(int $degree): ?AbstractNode;

    /**
     * @return bool
     */
    abstract public function wantsRekey(): bool;
}
```

### `ConsistentHashingService`

This class is the core service that implements the consistent hashing algorithm.

```php
<?php

namespace HuubVerbeek\ConsistentHashing;

class ConsistentHashingService
{
    /**
     * @param NodeCollection $nodeCollection
     */
    public function __construct(public NodeCollection $nodeCollection);

    /**
     * @param string $string
     * @return int
     */
    public function getDegree(string $string): int;

    /**
     * @param int $degree
     * @return Closure
     */
    public function degreeEqualOrSmallerThan(int $degree): Closure;

    /**
     * @param string $key
     * @return AbstractNode
     */
    public function resolve(string $key): AbstractNode;

    /**
     * @param int $degree
     * @return AbstractNode
     */
    public function nextNode(int $degree): AbstractNode;

    /**
     * @param int $degree
     * @return AbstractNode
     */
    public function previousNode(int $degree): AbstractNode;

    /**
     * @param AbstractNode $node
     * @return NodeCollection
     */
    public function addNode(AbstractNode $node): NodeCollection;

    /**
     * @param string $identifier
     * @return NodeCollection
     */
    public function removeNode(string $identifier): NodeCollection;

    /**
     * @param  AbstractNode  $from
     * @param  AbstractNode  $target
     * @param  Closure|null  $filter
     * @return void
 *   */
    public function moveItems(AbstractNode $from, AbstractNode $target, ?Closure $filter = null): void;
```
### Examples

#### 1: Basic Usage of ConsistentHashingService

```php
use HuubVerbeek\ConsistentHashing\ConsistentHashingService;
use HuubVerbeek\ConsistentHashing\StorageNode;
use HuubVerbeek\ConsistentHashing\StorageNodeCollection;
use Illuminate\Support\Str;

// Create a collection of storage nodes with their respective degrees
$nodesCollection = new StorageNodeCollection([
    new StorageNode(0, 0), // A node has a degree and an identifier. In this examplse it is an integer, however, normally it would be an identifier for store.
    new StorageNode(90, 1),
    new StorageNode(180, 2),
    new StorageNode(270, 3),
]);

// Create an instance of the ConsistentHashingService with the node collection
$service = new ConsistentHashingService($nodesCollection);

// Get a random degree from a given string (for example, generating a random degree for a cache key)
$degree = $service->getDegree(Str::random(10));

// Get the next node based on the degree of the random string
$nextNode = $service->nextNode($degree);

// Get the previous node based on the degree of the random string
$previousNode = $service->previousNode($degree);

// Resolve a key to the corresponding node
$key = 'test';

$resolvedNode = $service->resolve($key);

```

#### 2: Adding and Removing Nodes

```php
use HuubVerbeek\ConsistentHashing\StorageNode;
use HuubVerbeek\ConsistentHashing\StorageNodeCollection;
use HuubVerbeek\ConsistentHashing\ConsistentHashingService;

// Create an initial collection of storage nodes
$nodesCollection = new StorageNodeCollection([
    new StorageNode(0, 0),
    new StorageNode(90, 1),
    new StorageNode(180, 2),
    new StorageNode(270, 3),
]);

// Create an instance of the ConsistentHashingService with the node collection
$service = new ConsistentHashingService($nodesCollection);

// Add a new node to the collection with a degree of 45 and an identifier of 'new_node'
$newNode = new StorageNode(45, 'new_node');
$service->addNode($newNode);

// Remove a node from the collection by its identifier
$removedNodeId = 'new_node';
$service->removeNode($removedNodeId);
```
These examples demonstrate some of the key functionalities provided by the Laravel Consistent Hashing package. You can use these functionalities to distribute data across multiple nodes in your application while maintaining data stability and minimizing data migration when nodes are added or removed.