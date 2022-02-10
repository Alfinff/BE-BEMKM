<?php

namespace App\Services;

use App\Models\Footer;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use GrahamCampbell\Flysystem\Facades\Flysystem;

class FooterService
{

    public function cek($id) {
        try {
            $result = Footer::where('id', $id)->first();
            return $result;
        }
        catch (\Throwable $th) {
            DB::rollback();
            dd("Service error. " . $th->getMessage());
            return false;
        }
    }

    public function getOne() {
        try {
            $result = Footer::with('author')->first();
            return $result;
        }
        catch (\Throwable $th) {
            DB::rollback();
            dd("Service error. " . $th->getMessage());
            return false;
        }
    }

    public function update($request, $id) {
        try {
            $result = Footer::where('id', $id)->first();

            $result->update([
                'subtitle' => $request->subtitle,
                'alamat' => $request->alamat,
                'telepon' => $request->telepon,
                'email' => $request->email,
            ]);

            return $result;
        }
        catch (\Throwable $th) {
            DB::rollback();
            dd("Service error. " . $th->getMessage());
            return false;
        }
    }
}
