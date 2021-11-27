<?php

namespace App\Services;

use App\Models\News;
use App\Models\NewsCategory;
use App\Models\Master\Category;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class NewsService
{
    public function store($request) {
        try {
            $decodeToken = parseJwt($request->header('Authorization'));

            DB::beginTransaction();

            $uuid = generateUuid();

            $pic = '';

            if ($request->picture) {
                $fotoName = "news/images/";
                // $fotoPath = $fotoName.$request->foto;
                uploadFile($request->picture, $fotoName, "$uuid.png");
                $pic = $fotoName."$uuid.png";
            }

            $kategori = Category::where('name', $request->kategori)->first();
            if ($kategori === null){
                $kategori = Category::create([
                    'uuid' => generateUuid(),
                    'name' => $request->kategori,
                ]);
            }

            $result = News::create([
                'uuid' => $uuid,
                'title' => $request->title,
                'slug' => $request->slug,
                'content' => $request->content,
                'status' => $request->status,
                'picture' => $pic,
                'user_id' => $decodeToken->user->uuid
            ]);

            NewsCategory::create([
                'uuid' => generateUuid(),
                'category_id' => $kategori->uuid,
                'news_id' => $uuid
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
            $result = News::when($request->title, function ($query) use ($request) {
    			$query->where('title', 'like', '%' . $request->title . '%');
    		})
            ->with('author', 'newsCategory.category')
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

    public function getOne($id) {
        try {
            $result = News::where('uuid', $id)->with('author', 'newsCategory.category')->first();
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
            $result = News::where('uuid', $id)->with('author', 'newsCategory.category')->first();
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
            $result = News::where('uuid', $id)->first();

            $pic = $result->picture;

            if ($request->image) {
                $fotoName = "news/images/";
                // $fotoPath = $fotoName.$request->foto;
                uploadFile($request->image, $fotoName, "$uuid.png");
                $pic = $fotoName."$uuid.png";
            }

            $kategori = Category::where('name', $request->kategori)->first();
            if ($kategori === null){
                $kategori = Category::create([
                    'uuid' => generateUuid(),
                    'name' => $request->kategori,
                ]);
            }

            $result->update([
                'title' => $request->title,
                'slug' => $request->slug,
                'content' => $request->content,
                'status' => $request->status,
                'picture' => $pic
            ]);

            $deleted = NewsCategory::where('uuid', $id)->delete();
            
            NewsCategory::create([
                'uuid' => generateUuid(),
                'category_id' => $kategori->uuid,
                'news_id' => $uuid
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
            $result = News::where('uuid', $id)->first();
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
