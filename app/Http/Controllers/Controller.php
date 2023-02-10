<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0.0", description: "RSS News service documentation", title: "OpenApi Documentation",
    contact: (new OA\Contact(
        name: "Ivaniv Anton",
        email: "anton.o.ivaniv@gmail.com"
    )),
)]
#[OA\Server(url: '', description: "Основной API")]
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
