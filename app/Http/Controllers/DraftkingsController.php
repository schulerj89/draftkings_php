<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DraftGroup;

class DraftkingsController extends Controller
{
    /**
     * @param string $sport
     * @param string $type
     * 
     * @return \Illuminate\Http\Response
     */
    public function getPlayers($sport, $type)
    {
        $draftGroup = new DraftGroup($sport, $type);
        $players = $draftGroup->getPlayersFromDraftGroup();

        return response()->json($players);
    }

    /**
     * @param Illuminate\Http\Request $request
     * @param string $sport
     * @param string $type
     * 
     * @return \Illuminate\Http\Response
     */
    public function generateLineup(Request $request, $sport, $type)
    {
        $salaryBuffer = 0;

        if($request->get('buffer') != null) {
            $salaryBuffer = $request->get('buffer');
        }

        $draftGroup = new DraftGroup($sport, $type);
        $lineup = $draftGroup->generateLineup($salaryBuffer);

        return response()->json($lineup);
    }
}
