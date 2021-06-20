<?php

declare(strict_types=1);

use App\Entity\Bid;
use App\Utility\AutoBidManager;
use PHPUnit\Framework\TestCase;

final class AutoBidManagerTest extends TestCase
{
    public function testAutoBid(): void
    {
        $autoBidManagerMock = $this->getMockBuilder(AutoBidManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $bid = new Bid();
        $result = $autoBidManagerMock->autoBid($bid);
        $this->assertEquals(null, $result);
    }
}
