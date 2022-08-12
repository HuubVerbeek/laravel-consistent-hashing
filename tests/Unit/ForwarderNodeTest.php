<?php

namespace HuubVerbeek\ConsistentHashing\Tests\Unit;

use HuubVerbeek\ConsistentHashing\Contracts\ForwarderContract;
use HuubVerbeek\ConsistentHashing\Exceptions\InvalidDegreeException;
use HuubVerbeek\ConsistentHashing\ForwarderNode;
use HuubVerbeek\ConsistentHashing\Tests\TestCase;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Queue;

class TestForwarder implements ForwarderContract
{
    public function handle($request)
    {
        TestJob::dispatch($request);
    }
}

class TestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public mixed $request)
    {
    }

    public function handle()
    {
    }
}

class ForwarderNodeTest extends TestCase
{
    public function test_node_construction_fails_if_not_a_valid_degree()
    {
        $this->expectException(InvalidDegreeException::class);

        new ForwarderNode(400, 'A', TestForwarder::class);

        new ForwarderNode(-50, 'B', TestForwarder::class);
    }

    public function test_node_construction_succeeds_if_valid_degree()
    {
        new ForwarderNode(50, 'B', TestForwarder::class);

        new ForwarderNode(200, 'C', TestForwarder::class);

        $this->assertTrue(true);
    }

    public function test_forwarder_is_set_when_passed_as_argument_during_construction()
    {
        $forwarderNode = invade(new ForwarderNode(50, 'B', TestForwarder::class));

        $this->assertInstanceOf(ForwarderContract::class, $forwarderNode->forwarder);
    }

    public function test_request_can_be_forwarded_using_the_handle_method()
    {
        Queue::fake();

        $forwarderNode = new ForwarderNode(50, 'B', TestForwarder::class);

        $forwarderNode->handle(['mockRequest']);

        Queue::assertPushed(TestJob::class, function ($job) {
            return $job->request === 'mockRequest';
        });
    }
}
