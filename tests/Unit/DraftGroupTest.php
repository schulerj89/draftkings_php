<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\DraftGroup;

class DraftGroupTest extends TestCase
{
    private $draftGroup;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function setUp(): void
    {
        $this->draftGroup = new DraftGroup('mlb', 'featured');
    }
    public function test_draftgroupid()
    {
        $this->assertEquals(54126, $this->draftGroup->getDraftGroupId());
    }

    public function test_draftgrouprules()
    {
        $this->assertInstanceOf('App\Models\Rules', $this->draftGroup->getDraftGroupRules());
    }

    public function test_draftgroup_playersurl()
    {
        $this->assertEquals("https://api.draftkings.com/draftgroups/v1/draftgroups/{draft_group_id}/draftables?format=json", $this->draftGroup->getPlayersUrl());
    }

    public function test_draftgroup_builturl()
    {
        $this->assertEquals("https://api.draftkings.com/draftgroups/v1/draftgroups/54126/draftables?format=json", $this->draftGroup->buildDraftGroupUrl($this->draftGroup->getDraftGroupId(), $this->draftGroup->getPlayersUrl()));
    }

    public function test_draftgroupplayers()
    {
        $this->assertIsArray($this->draftGroup->getPlayersFromDraftGroup());
    }
}
