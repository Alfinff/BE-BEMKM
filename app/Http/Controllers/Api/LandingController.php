<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\NewsService;
use App\Services\VisiMisiService;
use App\Services\FakultasService;
use App\Services\KaryaUPNService;

class LandingController extends Controller
{
    public function __construct(Request $request, NewsService $newsService, FakultasService $fakultasService, VisiMisiService $visimisiService, KaryaUPNService $karyaupnService) {
        $this->request = $request;
        $this->newsService = $newsService;
        $this->fakultasService = $fakultasService;
        $this->visimisiService = $visimisiService;
        $this->karyaupnService = $karyaupnService;
    }

    public function index()
    {
        try
        {
            $beritautama = $this->newsService->getNewsUtama();
            $berita = $this->newsService->getNewsTiga($this->request);
            $karyaupn = $this->karyaupnService->getNew($this->request);

            $fakultas = $this->fakultasService->getAll($this->request);
            $visimisi = $this->visimisiService->getOne();

            return response()->json([
                'success' => true,
                'message' => 'OK',
                'code'    => 200,
                'data'    => [
                    'beritautama' => $beritautama,
                    'berita' => $berita,
                    'karyaupn' => $karyaupn,
                    'fakultas' => $fakultas,
                    'visimisi' => $visimisi
                ]
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
