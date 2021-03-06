<?php

namespace App\Services;

use App\Models\Organisasi;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use GrahamCampbell\Flysystem\Facades\Flysystem;

class OrganisasiService
{
    public function getAllOrganisasi() {
        try {
            $result = Organisasi::with('author')
            ->orderBy('created_at', 'desc')
            ->paginate(10);;
            return $result;
        }
        catch (\Throwable $th) {
            DB::rollback();
            dd("Service error. " . $th->getMessage());
            return false;
        }
    }

    public function store($request) {
        try {
            $decodeToken = parseJwt($request->header('Authorization'));

            DB::beginTransaction();

            $uuid = generateUuid();

            $pathfoto = '';

            if ($request->images) {
                $foto     = base64_decode($request->images);
                $pathfoto = "Organisasi/images/". $uuid . '.png';
                $upload   = Flysystem::connection('awss3')->put($pathfoto, $foto);
            }

            $result = Organisasi::create([
                'uuid' => $uuid,
                'title' => $request->title,
                'content' => $request->content,
                'picture' => $pathfoto,
                'user_id' => $decodeToken->user->uuid
            ]);

            DB::commit();

	   		return true;

        } catch (\Throwable $th) {
            DB::rollback();
            dd("Service error. " . $th->getMessage());
            return false;
        }
    }

    public function getAll($request) {
        try {
            $result = Organisasi::when($request->title, function ($query) use ($request) {
    			$query->where('title', 'like', '%' . $request->title . '%');
    		})
            ->with('author')
            ->orderBy('created_at', 'desc')
            ->paginate(25);

            return $result;
        }
        catch (\Throwable $th) {
            DB::rollback();
            dd("Service error. " . $th->getMessage());
            return false;
        }
    }

    public function getNew($request) {
        try {
            $result = Organisasi::when($request->title, function ($query) use ($request) {
    			$query->where('title', 'like', '%' . $request->title . '%');
    		})
            ->with('author')
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();

            return $result;
        }
        catch (\Throwable $th) {
            DB::rollback();
            dd("Service error. " . $th->getMessage());
            return false;
        }
    }

    public function getOne($id) {
        try {
            $result = Organisasi::where('uuid', $id)->with('author')->first();
            return $result;
        }
        catch (\Throwable $th) {
            DB::rollback();
            dd("Service error. " . $th->getMessage());
            return false;
        }
    }

    public function show($id) {
        try {
            $result = Organisasi::where('uuid', $id)->with('author')->first();
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
            $result = Organisasi::where('uuid', $id)->first();

            $pathfoto = $result->picture;

            if ($request->images) {
                $foto     = base64_decode($request->images);
                $pathfoto = "Organisasi/images/". $id . '.png';
                $upload   = Flysystem::connection('awss3')->put($pathfoto, $foto);
            }

            $result->update([
                'title' => $request->title,
                'content' => $request->content,
                'picture' => $pathfoto
            ]);

            return $result;
        }
        catch (\Throwable $th) {
            DB::rollback();
            dd("Service error. " . $th->getMessage());
            return false;
        }
    }

    public function delete($id) {
        try {
            $result = Organisasi::where('uuid', $id)->first();
            $result->delete();
            return true;
        }
        catch (\Throwable $th) {
            DB::rollback();
            dd("Service error. " . $th->getMessage());
            return false;
        }
    }
}
