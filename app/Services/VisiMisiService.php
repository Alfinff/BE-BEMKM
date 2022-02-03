<?php

namespace App\Services;

use App\Models\VisiMisi;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class VisiMisiService
{

    public function getOne() {
        try {
            $result = VisiMisi::with('author')->first();
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
            $result = VisiMisi::where('uuid', $id)->first();

            $pic = $result->picture;

            if ($request->image) {
                $fotoName = "VisiMisi/images/";
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
