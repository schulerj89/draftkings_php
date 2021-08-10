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

    /**
     * Determine if the salary is greater than the smallest salary in roster slot pool
     * 
     * @params int $rosterSlotId
     * @params int $salary
     * @params int $buffer
     * @return bool
     */
    public function isSalaryGreaterThanMin($rosterSlotId, $salary, $buffer = 0)
    {
        $min = 99999;

        foreach($this->playerPool[$rosterSlotId] as $_player) {
            if($_player->getSalary() < $min) {
                $min = $_player->getSalary();
            }
        }

        return $salary >= ($min + $buffer);
    }
}