<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use OpenApi\Attributes as OA;

// #[
//     OA\Info(
//         version: "1.0.0",
//         description: "This is the API description for the Laravel-Swagger-UI project.",
//         title: "Laravel-Swagger-UI",
//         termsOfService: 'http://swagger.io/terms/',
//         contact: new OA\Contact(
//             name: 'Suchakhri Dangthaisong',
//             email: 'chakhree.h@gamil.com',
//         ),
//         license: new OA\License(
//             name: 'Apache 2.0',
//             url: 'https://www.apache.org/licenses/LICENSE-2.0.html'
//         ),
//     )

// ]

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
