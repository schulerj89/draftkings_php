<?php

namespace App\Models;

use App\Models\DraftGroup;

class Roster
{
    protected $sport;

    protected $type;

    public function __construct($sport, $type)
    {
        $this->sport = $sport;
        $this->type = $type;
    }

    public function generateRoster()
    {
        // we need players to generate the roster
        $draftGroup = new DraftGroup($sport, $type);
        $players = $draftGroup->getPlayersFromDraftGroup();

        // we need the lineup template to generate roster
        $draftGroupRules = $draftGroup->getRules();
    }
}