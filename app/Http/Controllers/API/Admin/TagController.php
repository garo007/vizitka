<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Tag\TagResource;
use App\Http\Resources\Tag\TagsResource;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return TagsResource
     */
    public function index()
    {
        $tags = Tag::query()->get();

        return (new TagsResource($tags));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return TagResource
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $tag = Tag::query()->create($data);
        return (new TagResource($tag));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tag  $tag
     * @return TagResource
     */
    public function show(Tag $tag)
    {
        return (new TagResource($tag));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  \App\Models\Tag  $tag
     * @return TagResource
     */
    public function update(Request $request, Tag $tag)
    {
        $data = $request->all();
        $tag->update($data);
        return (new TagResource($tag));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();
        return response()->noContent();
    }
}
