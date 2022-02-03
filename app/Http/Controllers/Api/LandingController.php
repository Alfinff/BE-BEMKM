<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\KaryaUPNServices;
use App\Services\NewsService;
use App\Services\VisiMisiService;
use App\Services\FakultasService;
use App\Services\ProgramKerjaService;

class LandingController extends Controller
{
    public function __construct(Request $request, NewsService $newsService, FakultasService $fakultasService, VisiMisiService $visimisiService, KaryaUPNServices $karyaUPNService, ProgramKerjaService $programKerjaService) {
        $this->request = $request;
        $this->newsService = $newsService;
        $this->fakultasService = $fakultasService;
        $this->visimisiService = $visimisiService;
        $this->karyaUPNService = $karyaUPNService;
        $this->programKerjaService = $programKerjaService;
    }

    public function index()
    {
        try
        {
            $visimisi = $this->visimisiService->getOne();
            $beritautama = $this->newsService->getNewsUtama();
            $berita = $this->newsService->getNewsTiga($this->request);
            $karyaupn = $this->karyaUPNService->getNew($this->request);
            $programkerja = $this->programKerjaService->getNew($this->request);
            $fakultas = $this->fakultasService->getNew($this->request);

            return response()->json([
                'success' => true,
                'message' => 'OK',
                'code'    => 200,
                'data'    => [
                    'visimisi' => $visimisi,
                    'beritautama' => $beritautama,
                    'berita' => $berita,
                    'karyaupn' => $karyaupn,
                    'programkerja' => $programkerja,
                    'fakultas' => $fakultas,
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
