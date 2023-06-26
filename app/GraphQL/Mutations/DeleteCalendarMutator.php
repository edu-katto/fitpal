<?php

namespace App\GraphQL\Mutations;

use App\Models\Calendar;
use GraphQL\Error\Error;
use Illuminate\Support\Facades\Auth;

final class DeleteCalendarMutator
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        //Buscamos el registro a eliminar
        $calendar = Calendar::find($args['id']);

        //Validamos que existe registro con el id ingresado
        if (!$calendar){
            $errorMessage = 'no existe calendario con el id: ' . $args['id'];
            $error = new Error($errorMessage);
            throw $error;
            // Ocurrió un error al eliminar el objeto Calendar en la base de datos
        }

        //Validamos que el registro pertenezca al usuario loqueado
        $id_user = Auth::user()->getAuthIdentifier();

        if ($id_user != $calendar->user_id){
            $errorMessage = 'Acceso no permitido a este calendario: ' . $args['id'];
            $error = new Error($errorMessage);
            throw $error;
        }

        //Error al eliminar el calendario
        $delete = $calendar->delete();

        //Validamos que se borro de forma correcta
        if ($delete) {
            // El objeto Calendar se creó correctamente en la base de datos
            return $calendar;

        } else {
            // Ocurrió un error al guardar el objeto Calendar en la base de datos
            $errorMessage = 'Error al eliminar calendario: ' . $args['id'];
            $error = new Error($errorMessage);
            throw $error;
        }
    }
}
