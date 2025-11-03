<?php
namespace App\Service;

use Illuminate\Support\Facades\Cache;

class ProfileCacheService
{
    public function save($data)
    {
        Cache::put('profile', $data, now()->addMinutes(30));
    }

    public function get()
    {
        return Cache::get('profile');
    }

    public function forget()
    {
        Cache::forget('profile');
    }

    public function has()
    {
        return Cache::has('profile');
    }

}
