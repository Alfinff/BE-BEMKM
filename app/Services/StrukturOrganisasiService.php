<?php

namespace App\Services;

use App\Models\StrukturOrganisasi;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StrukturOrganisasiService
{

    public function getOne() {
        try {
            $result = StrukturOrganisasi::with('author')->first();
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
            $result = StrukturOrganisasi::where('uuid', $id)->first();

            $pic = $result->picture;

            if ($request->image) {
                $fotoName = "StrukturOrganisasi/images/";
                // $fotoPath = $fotoName.$request->foto;
                uploadFile($request->image, $fotoName, "$uuid.png");
                $pic = $fotoName."$uuid.png";
            }

            $result->update([
                'title' => $request->title,
                'visi' => $request->slug,
                'misi' => $request->content,
                'picture' => $pic
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
