<?php

namespace App\Services;

use App\Models\News;
use App\Models\NewsCategory;
use App\Models\Master\Category;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use GrahamCampbell\Flysystem\Facades\Flysystem;

class NewsService
{
    public function store($request) {
        try {
            $decodeToken = parseJwt($request->header('Authorization'));

            DB::beginTransaction();

            $uuid = generateUuid();

            $pathfoto = '';

            if ($request->images) {
                $foto     = base64_decode($request->images);
                $pathfoto = "news/images/". $uuid . '.png';
                $upload   = Flysystem::connection('awss3')->put($pathfoto, $foto);
            }

            $kategori = Category::where('name', 'LIKE', '%'.$request->kategori.'%')->first();
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
                'picture' => $pathfoto,
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

    public function getNew($request) {
        try {
            $result = News::when($request->title, function ($query) use ($request) {
    			$query->where('title', 'like', '%' . $request->title . '%');
    		})
            ->with('author', 'newsCategory.category')
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

    public function getNewsUtama() {
        try {
            $result = News::with('author', 'newsCategory.category')
            ->orderBy('created_at', 'desc')
            ->limit(1)
            ->first();

            return $result;
        }
        catch (\Throwable $th) {
            DB::rollback();
            dd("Service error. " . $th->getMessage());
            return false;
        }
    }


    public function getNewsTiga($request) {
        try {
            $result = News::when($request->title, function ($query) use ($request) {
    			$query->where('title', 'like', '%' . $request->title . '%');
    		})
            ->with('author', 'newsCategory.category')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

            return $result;
        }
        catch (\Throwable $th) {
            DB::rollback();
            dd("Service error. " . $th->getMessage());
            return false;
        }
    }

    public function getNewsDelapan($request) {
        try {
            $result = News::when($request->title, function ($query) use ($request) {
    			$query->where('title', 'like', '%' . $request->title . '%');
    		})
            ->with('author', 'newsCategory.category')
            ->orderBy('created_at', 'asc')
            ->limit(8)
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

            $pathfoto = $result->picture;

            if ($request->images) {
                $foto     = base64_decode($request->images);
                $pathfoto = "news/images/". $id . '.png';
                $upload   = Flysystem::connection('awss3')->put($pathfoto, $foto);
            }
            $kategori = Category::where('name', 'LIKE', '%'.$request->kategori.'%')->first();
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
                'picture' => $pathfoto
            ]);

            $deleted = NewsCategory::where('uuid', $id)->delete();

            NewsCategory::create([
                'uuid' => generateUuid(),
                'category_id' => $kategori->uuid,
                'news_id' => $id
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
