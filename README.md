### **Consistent Hashing**

Consistent hashing is a technique used in distributed computer systems, particularly in scenarios where data needs to be distributed across multiple nodes or servers while maintaining a high level of data availability and minimizing data movement during node additions or removals.

In traditional hash-based data distribution, data items are assigned to nodes based on their hash values. However, when the number of nodes changes (e.g., due to node failures or scaling up), the data distribution can be drastically affected, leading to data reassignment and redistribution, which can be resource-intensive and time-consuming.

Consistent hashing resolves this issue by providing a smooth way to map data items to nodes. It achieves this by organizing the hash space in a ring-like structure. Each node in the system is associated with one or more positions on the ring. When a data item needs to be stored or retrieved, its hash value is mapped onto the ring, and the closest node in a clockwise direction is selected to handle the operation.

This approach ensures that the majority of data items remain mapped to the same nodes even when the number of nodes changes. When a node is added or removed, only a fraction of the data needs to be remapped, making the process much more efficient and causing minimal disruption to the system.

## **Installation**

This package has not been published because it has not been extensively tested in a production environment. Use at your own risk.

## **Usage**

The following class is the core service that implements the consistent hashing algorithm.

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
     * @param string $key
     * @return AbstractNode
     */
    public function resolve(string $key): AbstractNode;

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
```

### Forwarding

The ConsistentHashingService takes a collection of nodes as a constructor argument. Each node in the following example takes a degree value (0 - 359), identifier and a `ForwarderContract` class string as arguments.

```php
$nodesCollection = new StorageNodeCollection([
    new ForwarderNode(0, 'instance_1', Router::class), // The router should implement the ForwarderContract that defines a `handle` method.
    new ForwarderNode(90, 'instance_2', Router::class)),
    new ForwarderNode(180, 'instance_3', Router::class)),
    new ForwarderNode(270, 'instance_4', Router::class)),
]);

// Create an instance of the ConsistentHashingService with the node collection
$service = new ConsistentHashingService($nodesCollection);
```
Let's imagine we are building a [reverse proxy](https://www.cloudflare.com/learning/cdn/glossary/reverse-proxy/). As our proxy is receiving requests from the internet we will need to forward them to one of many application instances. 
We can do so by taking the (hypothetical) unique `request_id` and resolving it to one of the nodes on the hash ring. 

```php
$node = $service->resolve($request_id); // returns an instance of a forwarder node.

$node->handle($args); //  delegates the call to the passed in Router class therefore allowing the user to define custom routing behavior.
```

There are a few things to note here:

1. A key always resolved to the same node.
2. Because our nodes are evenly spaced (0, 90, 180, 270) the amount of requests that every application instance will receive is roughly the same.

### Storing key-value data

Besides `forwarder` nodes the package support `storage` nodes. This allows us, for example, to store key-value in different cache instances.

```php
$nodesCollection = new StorageNodeCollection([
    new StorageNode(0, 'cache_1', CacheSetter::class, CacheGetter::class), // The setter and getter should respectively implement the Setter- and Getter contracts.
    new StorageNode(90, 'cache_2', CacheSetter::class, CacheGetter::class)),
    new StorageNode(180, 'cache_3', CacheSetter::class, CacheGetter::class)),
    new StorageNode(270, 'cache_4', CacheSetter::class, CacheGetter::class)),
]);
```
A storage node exposes the `set`, `get` and `all` methods. The `set` method is delegated to the CacheSetter class. The `get` and `all` methods are delegated to the CacheGetter class.

An example of setting and getting a value look like this:

```php
$service->resolve($cacheKey)->set($cacheKey, $data);

$data = $service->resolve($cacheKey)->get($cacheKey);
```

Because the hash function we are using is deterministic (e.g. the same key will return the same node) the service only needs the key to determine in which node the value is stored. 

### Removing our adding to the hash ring

In the process of resolving a key a `degree` (0 - 359) is computed. This degree is used to determine which node on the hash ring to use. The convention that is used is the following: 
`a degree resolves to the next node on the hash ring`. This means that 100 resolves to `cache_3` (degree 180) and 300 to `cache_1` (degree 0). 

We start running into problems if we want to remove a node. If we remove `cache_3` all degrees greater than 90 and smaller or equal to 180 are mapped to `cache_4` as this now is the next node on the ring. 
However this is not the node that was actually used when storing the items! 

This means that all values that were stored in `cache_3` should be moved to `cache_4`. The ConsistentHashingService takes care of this under the hood.

You can remove a node using:

```php
$service->removeNode($node);
```

Conversely, if we were to add a node between 180 and 270, let's say at degree 225, we need to move all the values in `cache_4` of which the keys map to a degree smaller or equal to 225 to the inserted node `cache_3.5`. The ConsistentHashingService does this in the background
when you use:

```php
$service->addNode($node);
```



