<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Services\OrganisasiService;
use Illuminate\Support\Facades\Validator;

class OrganisasiController extends Controller
{
    public function __construct(Request $request, OrganisasiService $service) {
        $this->request = $request;
        $this->service = $service;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try
        {
            $decodeToken = parseJwt($this->request->header('Authorization'));
            $uuid = $decodeToken->user->uuid;
            $user = User::where('uuid', $uuid)->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengguna tidak ditemukan',
                    'code'    => 404,
                ]);
            }
            else {
                $result = $this->service->getAll($this->request);
                if ($result->isEmpty()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Data Organisasi Kosong!',
                        'code'    => 404,
                    ]);
                }
                return response()->json([
                    'success' => true,
                    'message' => 'OK',
                    'code'    => 200,
                    'data'    => $result
                ]);
            }
        } catch (\Throwable $th) {
            dd($th->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $decodeToken = parseJwt($this->request->header('Authorization'));
            $uuid = $decodeToken->user->uuid;
            $user = User::where('uuid', $uuid)->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengguna tidak ditemukan',
                    'code'    => 404,
                ]);
            }
            else {
                $validator = Validator::make($this->request->all(), [
                    'title' => 'required',
                    'slug' => 'required',
                    'content' => 'required',
                    'status' => 'required',
                ]);
                if ($validator->fails()) {
                    return response()->json($validator->messages());
                } else {
                    $result = $this->service->store($this->request);

                    if (!$result) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Service Error',
                            'code'    => 500,
                        ]);
                    } else {

                        return response()->json([
                            'success' => true,
                            'message' => 'OK',
                            'code'    => 200,
                            'data'    => $result
                        ]);
                    }
                }
            }
        } catch (\Throwable $th) {
            dd('Controller error ' . $th->getMessage());
			return redirect()->back();
		}
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try
        {
            $decodeToken = parseJwt($this->request->header('Authorization'));
            $uuid = $decodeToken->user->uuid;
            $user = User::where('uuid', $uuid)->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengguna tidak ditemukan',
                    'code'    => 404,
                ]);
            }
            else {
                $result = $this->service->getOne($id);
                if (!$result) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Data Organisasi Kosong!',
                        'code'    => 404,
                    ]);
                }
                return response()->json([
                    'success' => true,
                    'message' => 'OK',
                    'code'    => 200,
                    'data'    => $result
                ]);
            }
        } catch (\Throwable $th) {
            dd($th->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $decodeToken = parseJwt($this->request->header('Authorization'));
            $uuid = $decodeToken->user->uuid;
            $user = User::where('uuid', $uuid)->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengguna tidak ditemukan',
                    'code'    => 404,
                ]);
            }
            else {

                if (!$result) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Service Error',
                        'code'    => 500,
                    ]);
                } else {
                    $result = $this->service->update($this->request, $id);
                    // $result = $this->service->store($this->request);
                        if (!$result) {
                            return response()->json([
                                'success' => false,
                                'message' => 'Service Error',
                                'code'    => 500,
                            ]);
                        }

                        return response()->json([
                            'success' => true,
                            'message' => 'OK',
                            'code'    => 200,
                            'data'    => $result
                        ]);
                    }
            }
        } catch (\Throwable $th) {
            dd('Controller error ' . $th->getMessage());
			return redirect()->back();
		}
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try
        {
            $decodeToken = parseJwt($this->request->header('Authorization'));
            $uuid = $decodeToken->user->uuid;
            $user = User::where('uuid', $uuid)->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengguna tidak ditemukan',
                    'code'    => 404,
                ]);
            }
            else {
                $result = $this->service->delete($id);

                return response()->json([
                    'success' => true,
                    'message' => 'OK',
                    'code'    => 200,
                ]);
            }
        } catch (\Throwable $th) {
            dd($th->getMessage());
        }
    }
}
