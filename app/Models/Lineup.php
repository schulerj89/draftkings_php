<?php

namespace App\Models;

use App\Models\PlayerPool;

class Lineup
{
    protected $rules;

    protected $players;

    /**
     * Constructor for lineup class
     * 
     * @params object App\Models\DraftGroup
     * @params object App\Models\Rules
     * @params string
     */
    public function __construct($players, $rules)
    {
        $this->rules = $rules;
        $this->players = $players;
    }

    public function generateLineup()
    {
        // we need players to generate the roster
        $playerPoolObj = new PlayerPool($this->players);
        $playerPool = $playerPoolObj->getPlayerPool();

        // we need the lineup template to generate roster
        $draftGroupRules = $this->rules;

        // salary cap
        $salaryCapRules = $draftGroupRules->getSalaryCapRules();
        $salaryCap = $salaryCapRules['maxValue'];

        // lineup template
        $lineupTemplate = $draftGroupRules->getLineupTemplate();

        $generatedLineup = array();
        $alreadySelected = array();

        foreach($lineupTemplate as $_template) {
            $rosterId = $_template['rosterSlot']['id'];
            $playersInSlot = $playerPool[$rosterId];
            $randomNumber = rand(0, count($playersInSlot) - 1);
            $player = $playersInSlot[$randomNumber];

            if(!in_array($player->getName(), $alreadySelected)) {
                $alreadySelected[] = $player->getName();
                $salaryCap -= $player->getSalary();
                $generatedLineup[] = array($player->getPosition(), $player->getName(), $player->getSalary(), $player->getFPPG());
            }
        }

        return array($generatedLineup, $salaryCap);
    }
}