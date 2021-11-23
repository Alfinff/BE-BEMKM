<?php

use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Ramsey\Uuid\Uuid;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

function writeLog($message)
{
	try {
		\Log::error($message);
	    return response()->json([
	    	'success' => false,
	    	'message' => env('APP_DEBUG') ? $message : 'Terjadi kesalahan',
	    	'code'    => 500,
	    ]);
	} catch (Exception $e) {
		return false;
	}
}

function generateUuid()
{
	try {
		return Uuid::uuid4();
	} catch (Exception $e) {
		return false;
	}
}

function uploadFile($file, $path, $filename)
{
	Storage::disk('s3')->putFileAs($path, $file, $filename, [
	    'visibility' => 'public',
	]);

	return true;
}

function showFile($path)
{
	return Storage::disk('s3')->temporaryUrl($path, Carbon::now()->addMinutes(5000));
}

function deleteFile($path)
{
	Storage::disk('s3')->delete($path);

	return true;
}

function generateOtp()
{
	return substr(str_shuffle('1234567890'), 0, 6);
}

function formatTanggal($tanggal)
{
	return date('Y-m-d H:i:s', strtotime($tanggal));
}

function generateJwt(User $user)
{
	try {
		$key = '';
	    $key  = str_shuffle('QWERTYUIOPASDFGHJKLZXCVBNM1234567890');

		$dataUser = [];
		$dataUser = [
			'id'           	=> $user->id ?? '',
			'uuid'          => $user->uuid ?? '',
			'name'          => $user->name ?? '',
			'email'         => $user->email ?? '',
		];

		$payload = [];
	    $payload = [
			'iss'  => 'lumen-jwt',
			'iat'  => time(),
			'exp'  => time() + 60 * 60 * 24,
			'key'  => $key,
			'user' => $dataUser,
	    ];

	    return JWT::encode($payload, env('JWT_SECRET'));
	} catch (Exception $e) {
		return writeLog($e->getMessage());
	}
}

function parseJwt($token)
{
	try {
		return JWT::decode($token, env('JWT_SECRET'), array('HS256'));
	} catch (Exception $e) {
		return writeLog($e->getMessage());
	}
}
