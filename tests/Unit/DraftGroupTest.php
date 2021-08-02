<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\DraftGroup;

class DraftGroupTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_draftgroupid()
    {
        $draftGroup = new DraftGroup('mlb', 'featured');
        $draftGroupId = $draftGroup->getDraftGroupId();
        $this->assertEquals(54119, $draftGroupId);
    }
}
