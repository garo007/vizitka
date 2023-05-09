<?php

namespace App\Http\Controllers\API\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Rubrics\RubricStoreUpdateRequest;
use App\Http\Requests\Rubrics\RubricUpdateRequest;
use App\Http\Resources\RubricResource;
use App\Http\Resources\RubricsResource;
use App\Models\Rubric;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;

class RubricController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return RubricsResource
     */
    public function index(): RubricsResource
    {
        $rubrics = Rubric::query()->get();

        return (new RubricsResource($rubrics));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param Rubric $rubric
     * @return RubricResource
     */
    public function show(Rubric $rubric)
    {
        return (new RubricResource($rubric));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param RubricStoreUpdateRequest $request
     * @param Rubric $rubric
     * @param FileService $fileService
     * @return RubricResource
     */
    public function update(RubricStoreUpdateRequest $request, Rubric $rubric, FileService $fileService)
    {
        $data = $request->validated();
        $file = Arr::only($data, 'icon');

        $rubric->update(['name' => $data['name']]);

        if ($file) {
            if ($rubric->getIconAttribute()) {
                $fileService
                    ->setType('icon')
                    ->setDirectory('icons')
                    ->update($data['icon'], $rubric);
            } else {
                $fileService
                    ->setModelId($rubric->id)
                    ->setModelType(Rubric::class)
                    ->setType('icon')
                    ->setDirectory('icons')
                    ->store($data['icon']);
            }
        }
        return (new RubricResource($rubric));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
