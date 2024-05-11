<?php

namespace App\Http\Controllers;

use App\Http\Resources\SymptomResource;
use App\Models\Disease;
use App\Models\Symptom;
use App\Models\SymptomAndDisease;
use Illuminate\Http\Request;

class DiseasePredictionController extends Controller
{
    public function predictDisease(Request $request)
    {
        // Assuming symptoms are passed as a comma-separated string in "symptoms" field
        $inputSymptoms = explode(',', $request->input('symptoms'));

        // Retrieve all diseases
        $allDiseases = SymptomAndDisease::all();

        // Initialize an array to store the similarity scores
        $diseaseScores = [];

        // Compare input symptoms with each disease
        foreach ($allDiseases as $disease) {
            $diseaseSymptoms = $disease->symptoms;

            $commonSymptoms = array_intersect($inputSymptoms, $diseaseSymptoms);

            // Compute a similarity score as a percentage
            $score = count($commonSymptoms) / count($diseaseSymptoms) * 100;

            $diseaseScores[$disease->disease] = round($score, 2); // Round to 2 decimal places
        }

        // Sort scores in descending order
        arsort($diseaseScores);

        // Find the disease with the highest score
        $finalPrediction = array_keys($diseaseScores, max($diseaseScores))[0];

        return response()->json([
            'final_prediction' => __($finalPrediction),
            'scores' => $diseaseScores,

        ]);
    }

    public function getSymptoms()
    {
        $symptoms = Symptom::paginate(10);
        return SymptomResource::collection($symptoms);
    }

//    public function predictDisease(Request $request)
//    {
//        // Assuming symptoms are passed as a comma-separated string in "symptoms" field
//        $inputSymptoms = explode(',', $request->input('symptoms'));
//        $inputSymptomCount = count($inputSymptoms);
//
//        // Retrieve all diseases
//        $allDiseases = SymptomAndDisease::all();
//
//        // Initialize an array to store the similarity scores
//        $diseaseScores = [];
//
//        // Compare input symptoms with each disease
//        foreach ($allDiseases as $disease) {
//            $diseaseSymptoms = $disease->symptoms;
//            $commonSymptoms = array_intersect($inputSymptoms, $diseaseSymptoms);
//
//            // Calculate score with weighted factors
//            $matchPercentage = count($commonSymptoms) / count($diseaseSymptoms) * 100;
//            $matchCoverage = count($commonSymptoms) / $inputSymptomCount * 100;
//
//            // Combine both percentages for scoring (adjust weightings if necessary)
//            $score = round((0.7 * $matchPercentage) + (0.3 * $matchCoverage), 2);
//
//            $diseaseScores[$disease->disease] = $score;
//        }
//
//        // Sort scores in descending order
//        arsort($diseaseScores);
//
//        // Find the disease with the highest score
//        $finalPrediction = array_keys($diseaseScores, max($diseaseScores))[0];
//
//        return response()->json([
//            'final_prediction' => $finalPrediction,
//            'scores' => $diseaseScores
//        ]);
//    }

//    public function predictDisease(Request $request)
//    {
//        // Assuming symptoms are passed as a comma-separated string in "symptoms" field
//        $inputSymptoms = explode(',', $request->input('symptoms'));
//        $inputSymptomCount = count($inputSymptoms);
//
//        // Retrieve all diseases
//        $allDiseases = SymptomAndDisease::all();
//
//        // Initialize an array to store the similarity scores
//        $diseaseScores = [];
//
//        // Compare input symptoms with each disease
//        foreach ($allDiseases as $disease) {
//            $diseaseSymptoms = $disease->symptoms;
//            $commonSymptoms = array_intersect($inputSymptoms, $diseaseSymptoms);
//
//            // Calculate scores
//            $matchDiseasePercentage = count($commonSymptoms) / count($diseaseSymptoms) * 100;
//            $matchInputPercentage = count($commonSymptoms) / $inputSymptomCount * 100;
//            $commonCount = count($commonSymptoms);
//
//            // Combine both percentages for scoring (adjust weightings if necessary)
//            $score = round((0.5 * $matchDiseasePercentage) + (0.5 * $matchInputPercentage) + $commonCount, 2);
//
//            $diseaseScores[$disease->disease] = $score;
//        }
//
//        // Sort scores in descending order
//        arsort($diseaseScores);
//
//        // Find the disease with the highest score
//        $finalPrediction = array_keys($diseaseScores, max($diseaseScores))[0];
//
//        return response()->json([
//            'final_prediction' => $finalPrediction,
//            'scores' => $diseaseScores
//        ]);
//    }
}
