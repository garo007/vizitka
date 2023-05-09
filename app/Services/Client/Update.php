<?php

namespace App\Services\Client;

use App\Models\Client;
use Exception;
use Illuminate\Support\Arr;

class Update
{
    protected $fileService;
    protected $client;

    public function __invoke($data, $fileService)
    {
        $this->client = auth()->guard('client')->user();

        $clientFromEmail = Client::query()->where(['email' => $data['email']])->first();
        if ($clientFromEmail && $clientFromEmail->id !== $this->client->id) {
            throw new Exception(json_encode(['email' => __('exception.client.unique_email')]));
        }

        $this->fileService = $fileService;

        $dataUpdate = Arr::except($data, ['state_certificate_of_company', 'license', 'logo', 'privacy_policy']);

        $this->client->update($dataUpdate);

        if (!empty($data['state_certificate_of_company'])) {
            $this->uploadStateCertificateOfCompany($data['state_certificate_of_company']);
        }

        if (!empty($data['license'])) {
            $this->uploadLicense($data['license']);
        }

        if (!empty($data['logo'])) {
            $this->uploadLogo($data['logo']);
        }

        if (!empty($data['privacy_policy'])) {
            $this->uploadPrivacyPolicy($data['privacy_policy']);
        }

        return $this->client;
    }

    protected function uploadStateCertificateOfCompany($file)
    {
        $this->fileService
            ->setDirectory('state-certificate-of-companies')
            ->setType('stateCertificateOfCompany')
            ->update($file, $this->client);
    }

    protected function uploadLicense($file)
    {
        if ($this->client->getLicenseAttribute()) {
            $this->fileService
                ->setType('license')
                ->setDirectory('licenses')
                ->update($file, $this->client);
        } else {
            $this->fileService
                ->setModelId($this->client->id)
                ->setModelType(Client::class)
                ->setType('license')
                ->setDirectory('licenses')
                ->store($file);
        }
    }

    protected function uploadLogo($file)
    {
        if ($this->client->getLogoAttribute()) {
            $this->fileService
                ->setType('logo')
                ->setDirectory('logos')
                ->update($file, $this->client);
        } else {
            $this->fileService
                ->setModelId($this->client->id)
                ->setModelType(Client::class)
                ->setType('logo')
                ->setDirectory('logos')
                ->store($file);
        }
    }

    protected function uploadPrivacyPolicy($file)
    {
        if ($this->client->getPrivacyPolicyAttribute()) {
            $this->fileService
                ->setType('privacyPolicy')
                ->setDirectory('privacy-policies')
                ->update($file, $this->client);
        } else {
            $this->fileService
                ->setModelId($this->client->id)
                ->setModelType(Client::class)
                ->setType('privacy_policy')
                ->setDirectory('privacy-policies')
                ->store($file);
        }
    }
}
