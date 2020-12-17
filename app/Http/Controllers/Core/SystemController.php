<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use App\Http\Resources\Core\ErrorResource;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SystemController extends Controller
{
    public function download(string $id): BinaryFileResponse
    {
        if (file_exists(storage_path("app/public/excel/$id.xlsx")))
            return response()->download(storage_path())->deleteFileAfterSend();
        else
            throw new HttpResponseException(response()->json(new ErrorResource("dlapi.file_expired")));
    }

    public function info()
    {
        return response()->json(config("ApiConfig"));
    }
}
