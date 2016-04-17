<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\ItemList;

class MatchController extends Controller
{
    public function getMatch (Request $request) {
		$this->validate($request, [
            'list_id' => 'required|max:255',
        ]);

        $list = ItemList::findOrFail($request['list_id']);

        $allLists = ItemList::whereRaw("due_date = '".$list->due_date."' AND id != '".$list->id."'")->get();
        //dd($allLists);
        if(empty($allLists)) {
        	return null;
        }
        $bestList = ["list_id" => null, "score" => 0.00];

        foreach ($allLists as $key => $l) {
        	$similiarityScore = 0;

        	foreach ($list->items as $item1) {
        		foreach ($l->items as $item2) {
        			if ($item1->name == $item2->name){
        				$similiarityScore++;
        			}
        		}
        	}
			$similarityPercentage = $similiarityScore / min($list->items()->count(), $l->items()->count());
			$distanceScore = $this->calculateDistanceScore(floatval($list->longitude), floatval($l->longitude), floatval($list->latitude), floatval($l->latitude));

			$totalScore = $distanceScore * $similarityPercentage;
			if ($bestList['score']< $totalScore){
				$bestList['list_id'] = $l->id;
				$bestList['score'] = $totalScore;
			}
        }

        return response()->json($bestList);
    }

    private function calculateDistanceScore($Long1, $Long2, $Lat1, $Lat2) {
		$d2r = 0.0174532925199433;
		$dLong = ($Long1 - $Long2)*$d2r;
		$dLat = ($Lat1 - $Lat2)*$d2r;
		$fds = $Lat2 * $d2r;
		$ds = $Lat1 * $d2r;

		$ar = pow(sin($dLat/2.0),2) + cos($Lat2 * $d2r) * cos($Lat1 * $d2r) * pow(sin($dLong / 2.0),2);
		$cr = 2*atan2(sqrt($ar), sqrt(1-$ar));

		$distance = 6367 * $cr;

		if ($distance <= 5){
			$score = 10;
		} elseif($distance >5 && $distance <=10){
			$score = 8;
		}elseif($distance >10 && $distance <=15){
			$score = 5;
		}elseif($distance >15 && $distance <=20){
			$score = 3;
		}elseif($distance >20 && $distance <=50){
			$score = 1;
		}else{
			$score = 0;
		}

		return $score;
    }
}
