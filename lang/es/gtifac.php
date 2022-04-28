<?php

use Illuminate\Support\Facades\DB;
/*
|--------------------------------------------------------------------------
| Authentication Language Lines
|--------------------------------------------------------------------------
|
| The following language lines are used during authentication for various
| messages that we need to display to the user. You are free to modify
| these language lines according to your application's requirements.
|
*/

$data = [];
$resulset = DB::connection('mysql')->table('mensajes_gtifacs')->get(['nombre', 'mensaje']);

foreach ($resulset as $row) {
    $data[$row->nombre] = $row->mensaje;
}

return $data;
