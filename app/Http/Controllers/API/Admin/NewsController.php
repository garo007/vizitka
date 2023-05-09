<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\NewsResource;
use App\Models\News;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;

class NewsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['admin_middleware'])->except(['index', 'show']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return NewsResource
     */
    public function index()
    {
        $news = News::query()->latest()->get();
        return (new NewsResource($news));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return NewsResource
     */
    public function store(Request $request,  FileService $fileService)
    {
        $data = $request->all();
        $file = Arr::only($request->all(), ['image']);

        $news = News::query()->create($data);
        if (!empty($file['image'])) {
            $fileService
                ->setModelId($news->id)
                ->setModelType(News::class)
                ->setType('image')
                ->setDirectory('image')
                ->store($file['image']);
        }

        return (new NewsResource($news));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\News  $news
     * @return NewsResource
     */
    public function show(News $news)
    {
        return (new NewsResource($news));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param News $news
     * @param FileService $fileService
     * @return NewsResource
     */
    public function update(Request $request, News $news, FileService $fileService)
    {
        $data = $request->all();
        $file = Arr::only($request->all(), ['image']);
        $news->files()->delete();

        $news->update($data);
        if (!empty($file['image'])) {
            $fileService
                ->setModelId($news->id)
                ->setModelType(News::class)
                ->setType('image')
                ->setDirectory('image')
                ->store($file['image']);
        }

        return (new NewsResource($news));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function destroy(News $news)
    {
        $news->files()->delete();
        $news->delete();
        return response(['message' => __('response.admin.delete'), 'success' => true]);
    }
}
