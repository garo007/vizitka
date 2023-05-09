<?php

namespace App\Services\Admin\Client;

use App\Jobs\UpdateRole;
use App\Models\Client;
use Exception;
use Illuminate\Support\Facades\File;
use \App\Models\File as FileModel;

class SetRolesPermissions
{
    public function __invoke($data, $client)
    {
        if (!$client->filled) {
            throw new Exception(__('exception.admin.filled'));
        }

        $someRoles = $this->arrayEqual($data['roles'], $client->getRoleNames()->all());
        $somePermission = $this->arrayEqual($data['permissions'], $client->getPermissionNames()->all());

        if ($someRoles && $somePermission) {
            throw new Exception(__('exception.admin.needed_change'));
        }

        if ($client->getRoleNames()) $client->syncRoles();

        $client->assignRole($data['roles']);

        $client->syncPermissions();

        if (in_array('seller', $data['roles'])) {
            if (in_array('full-access', $data['permissions'])) {
                $client->syncPermissions('full-access');
            } else {
                $client->syncPermissions($data['permissions']);
            }
        }

        $fileName = $this->sendContract($client);
        UpdateRole::dispatch($client, $fileName);

        return __('response.admin.set_role_permissions', ['company_name' => $client->company_name]);
    }

    protected function sendContract($client)
    {
        $fileName = str_replace('-', '', str()->uuid() . '.pdf');

        File::copy(public_path('contracts/contract.pdf'), storage_path('app/public/contracts/' . $fileName));

        FileModel::query()->create([
            'model_id' => $client->id,
            'model_type' => Client::class,
            'name' => 'Contract ' . date('Y-m-d'),
            'path' => 'contracts/' . $fileName,
            'type' => 'contract'
        ]);

        return $fileName;
    }

    protected function arrayEqual(array $new, array $current)
    {
        return (
            is_array($new)
            && is_array($current)
            && count($new) == count($current)
            && array_diff($new, $current) === array_diff($current, $new)
        );
    }
}
