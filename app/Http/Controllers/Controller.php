<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Traits\ApiResponserTest;
use App\Traits\TransformDataTest;

class Controller extends BaseController
{
    use ApiResponserTest, TransformDataTest;
    //
}
