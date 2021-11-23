<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function authenticate(User $user)
    {

        $validator = Validator::make($this->request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return writeLogValidation($validator->errors());
        }

        try {
            $user = User::where('email', $this->request->email)->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengguna tidak ditemukan!',
                    'code'    => 404,
                ]);
            }

            if (Hash::check($this->request->password, $user->password)) {
                $token = generateJwt($user);

                if (!$token) {
                    return writeLog('Terjadi kesalahan');
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Akses token',
                    'code'    => 200,
                    'data'    => [
                        'token'  => $token,
                    ],
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Email Atau Password Salah',
                'code'    => 404,
            ]);
        } catch (\Throwable $th) {
            return dd($th);
        }

    }

    public function decodetoken(Request $request) {
        try {
            $decodeToken = parseJwt($this->request->header('Authorization'));

            return json_encode($decodeToken->user);
        } catch (\Throwable $th) {
            return writeLog($th->getMessage());
        }
    }
}
