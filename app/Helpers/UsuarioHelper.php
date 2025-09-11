<?php

// app/Helpers/UsuarioHelper.php

namespace App\Helpers;

use App\Models\Usuario;
use Illuminate\Support\Facades\Session;

class UsuarioHelper
{
    public static function actual()
    {
        $id = Session::get('id');
        return $id ? Usuario::find($id) : null;
    }
}
