<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\NewsService;
use App\Services\FakultasService;

class LandingController extends Controller
{
    public function __construct(Request $request, NewsService $newsService, FakultasService $fakultasService) {
        $this->request = $request;
        $this->newsService = $newsService;
        $this->fakultasService = $fakultasService;
    }

    public function index()
    {
        try
        {
            $news = $this->newsService->getNew($this->request);
            // $news = array_slice($news, 0, 3);

            $fakultas = $this->fakultasService->getAll($this->request);

            return response()->json([
                'success' => true,
                'message' => 'OK',
                'code'    => 200,
                'news'    => $news,
                'fakultas'=> $fakultas
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => False,
                'message' => "Controller Error $th",
                'code'    => 500,
            ]);
        }
    }
}
