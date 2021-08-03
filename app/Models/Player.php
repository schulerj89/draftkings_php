<?php

namespace App\Models;

class Player
{
    protected $playerData;

    protected $probablePositions = array('SP', 'RP');

    protected $injuryStatus = array('IL', 'OUT');

    public function __construct($player)
    {
        $this->playerData = $player;
    }

    public function getSalary()
    {
        return $this->playerData['salary'];
    }

    public function getStatus()
    {
        return $this->playerData['status'];
    }

    public function getName()
    {
        return $this->playerData['displayName'];
    }

    public function getPosition()
    {
        return $this->playerData['position'];
    }

    public function getFPPG()
    {
        return $this->playerData['draftStatAttributes'][0]['value'];
    }

    public function isInjured()
    {
        return in_array($this->getStatus(), $this->injuryStatus);
    }

    public function isProbable()
    {
        // some sports use this field to determine if the player is probable to play (Ex: MLB)
        if(isset($this->playerData['playerGameAttributes']) && !empty($this->playerData['playerGameAttributes'])) {
            foreach($this->playerData['playerGameAttributes'] as $_gameAttribute) {
                if($_gameAttribute['id'] == 1 && in_array($this->getPosition(), $this->probablePositions)) {
                    return (bool)$_gameAttribute['value'];
                }

                // used for MLB position that are no pitchers
                // this value will have confirmed batting order
                if($_gameAttribute['id'] == 99) {
                    return $_gameAttribute['value'] > 0;
                }
            }

            return false;
        }

        return true;
    }
}