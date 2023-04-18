<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

    /**
     * @OA\Info(
     *      version="1.0.0",
     *      title="Awahan API Documentation",
     *      description="Swagger OpenApi description",
     *      @OA\Contact(
     *          email="subham.5ine@gmail.com"
     *      ),
     *      @OA\License(
     *          name="Nginx 1.22.1",
     *          url="http://nginx.org/LICENSE"
     *      )
     * )
     *
     * @OA\Server(
     *      url=L5_SWAGGER_CONST_HOST,
     *      description="Awahan API Server"
     * )

     *
     * @OA\Tag(
     *     name="Auth",
     *     description="API Endpoints of Authentication"
     * )
     */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
