<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ManageRequest;
use App\Http\Requests\Admin\SetRolesPermissionsRequest;
use App\Http\Resources\Admin\ClientsResource;
use App\Http\Resources\Admin\ClientResource;
use App\Models\Client;
use App\Services\Admin\Client\Accept;
use App\Services\Admin\Client\Reject;
use App\Services\Admin\Client\SetRolesPermissions;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Exception;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;

class ClientController extends Controller
{
    public function __construct()
    {
        $this->middleware(['admin_middleware'])->except(['index', 'show']);
    }


    public function index(Request $request): ClientsResource
    {
        $clients = Client::query()
            ->latest()
            ->filter($request->get('filters'))
            ->get();

        return (new ClientsResource($clients));
    }

    public function show(Client $client): ClientResource
    {
        return (new ClientResource($client));
    }

    public function store(Request $request, FileService $fileService): ClientsResource
    {
        $data = Arr::except($request->all(), ['logo', 'images', 'tag_ids']);
        $tagIds = explode(',', $request->get('tag_ids'));
        $client = Client::query()->create($data);
        $client->tags()->sync($tagIds);
        if (!empty($files['logo'])) {
            $fileService
                ->setModelId($client->id)
                ->setModelType(Client::class)
                ->setType('logo')
                ->setDirectory('logos')
                ->store($files['logo']);
        }

        if (!empty($files['images'])) {
            foreach ($files['images'] as $image) {
                $fileService
                    ->setModelId($client->id)
                    ->setModelType(Client::class)
                    ->setType('image')
                    ->setDirectory('images')
                    ->store($image);
            }
        }

        return (new ClientsResource($client));
    }


    public function update(Request $request, Client $client, FileService $fileService): ClientsResource
    {
        $data = Arr::except($request->all(), ['logo', 'images', '_method', 'created_at', 'updated_at', 'id', 'tag_ids']);
        $files = Arr::only($request->all(), ['logo', 'images']);
        $tag_ids = explode(',', $request->get('tag_ids'));
        $client->tags()->sync($tag_ids);
        $client->files()->delete();

        $client->update($data);

        $tagIds = explode(',', $request->get('tag_ids'));
        $client->tags()->sync($tagIds);

        if (!empty($files['logo']) && ($files['logo'] instanceof UploadedFile)) {
            $fileService
                ->setModelId($client->id)
                ->setModelType(Client::class)
                ->setType('logo')
                ->setDirectory('logos')
                ->store($files['logo']);
        }

        if (!empty($files['images']) && !is_string($files['images'])) {
            foreach ($files['images'] as $image) {
                $fileService
                    ->setModelId($client->id)
                    ->setModelType(Client::class)
                    ->setType('image')
                    ->setDirectory('images')
                    ->store($image);
            }
        }

        return (new ClientsResource($client));
    }

    /**
     * @param Client $client
     * @return Response
     */
    public function delete(Client $client)
    {
        $client->files()->delete();
        $client->delete();
        $client->tags()->sync([]);

        return response(['message' => __('response.admin.delete'), 'success' => true]);
    }
}
