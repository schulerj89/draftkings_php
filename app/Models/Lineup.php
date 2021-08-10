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
     * @param App\Models\DraftGroup
     * @param App\Models\Rules
     */
    public function __construct($players, $rules)
    {
        $this->players = $players;
        $this->rules = $rules;
    }

    /**
     * Generate a lineup based on the player pool
     * 
     * @param int $salaryBuffer
     * @return array
     */
    public function generateLineup($salaryBuffer = 0)
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

        // number of position
        $positionsCount = count($lineupTemplate);

        $generatedLineup = array();
        $alreadySelected = array();

        foreach ($lineupTemplate as $_template) {
            $rosterId = $_template['rosterSlot']['id'];
            $playersInSlot = $playerPool[$rosterId];

            foreach ($playersInSlot as $_player) {
                if($positionsCount > 1) {
                    $tempSalaryMax = ($salaryCap - $_player->getSalary()) / ($positionsCount-1);
                    $enoughMoney = $playerPoolObj->isSalaryGreaterThanMin($rosterId, $tempSalaryMax, $salaryBuffer);
                    
                } else {
                    $enoughMoney = $_player->getSalary() <= $salaryCap;
                }

                // only select player who we haven't selected and there is enough money to get the player              
                if (!in_array($_player->getName(), $alreadySelected) && $enoughMoney) {
                    $player = $_player;
                    break;
                } else {
                    $player = array(); // need to find a better way to handle not finding players
                }
            }

            // need to find a better way to handle not finding players
            if(empty($player)) {
                $generatedLineup[] = array('N/A', 'Player not found', 0, 0);
                continue;
            }

            $alreadySelected[] = $player->getName();
            $salaryCap -= $player->getSalary();
            $positionsCount--;
            $generatedLineup[] = array($player->getPosition(), $player->getName(), $player->getSalary(), $player->getFPPG());

            // let's avoid dividing by zero
            if($positionsCount > 0) {
                $playerSalaryMax = $salaryCap / $positionsCount;
            }
        }

        return array($generatedLineup, $salaryCap);
    }
}