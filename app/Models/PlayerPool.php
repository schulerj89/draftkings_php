<?php

namespace App\Models;

use App\Models\Player;

class PlayerPool
{
    protected $playerPool;

    public function __construct($players)
    {
        $this->playerPool = [];

        foreach($players as $_player) {
            $player = new Player($_player);
            if($player->isInjured() || !$player->isProbable()) {
                continue; // skip if they are injured/not probable
            } 

            // put player objects into array by their slot id (Ex: RB/FLEX, 1B/2B)
            $this->playerPool[$_player['rosterSlotId']][] = $player;
        }
    }

    public function getPlayerPool()
    {
        return $this->playerPool;
    }
}