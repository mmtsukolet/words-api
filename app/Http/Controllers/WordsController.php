<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client as GuzzleClient;
use Carbon\Carbon;

use App\Models\PreviousSearches;

class WordsController extends Controller
{
    /**
     * Retrieve all searches
     * @params Request $request
     */
    public function list(Request $request)
    {
        $model = PreviousSearches::where('is_display', 1)->get();
        return response()->json([
            'success' => true,
            'error' => false,
            'data' => $model
        ]);
    }

    /**
     * Handles search feature
     * @params Request $request
     */
    public function search(Request $request)
    {
        try {

            $_post = $request->all();

            $term = $_post['term'];
            $params = [
                'when' => '2022-05-16T18:12:58.529Z',
                'encrypted' => '8cfdb188e722969beb9707bee758bfbbaeb5250935fd9eb8'
            ];

            $url = "mashape/words/".$term."?".http_build_query($params);
            $client = new GuzzleClient(['base_uri' => "https://www.wordsapi.com/"]);
            $response = $client->get($url);
            $results = json_decode($response->getBody()->getContents(), true);

            $model = new PreviousSearches();
            $model->search_term = $_post['term'];
            $model->is_display = 1;
            $searchedItm = $model->save();

            return response()->json([
                'success' => true,
                'error' => false,
                'data' => $results
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => true,
                'data' => $e->getMessage()
            ]);
        }
    }
}
