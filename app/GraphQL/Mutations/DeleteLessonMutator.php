<?php

namespace App\GraphQL\Mutations;

use App\Models\Calendar;
use App\Models\Lesson;
use GraphQL\Error\Error;
use Illuminate\Support\Facades\Auth;

final class DeleteLessonMutator
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        //Buscamos el registro a eliminar
        $clase = Lesson::find($args['id']);

        //Validamos que existe registro con el id ingresado
        if (!$clase){
            // Ocurrió un error al eliminar el objeto Calendar en la base de datos
            $errorMessage = 'No existe clase con el id: ' . $args['id'];
            $error = new Error($errorMessage);
            throw $error;
        }

        //Validamos que el registro pertenezca al usuario loqueado
        $id_user = Auth::user()->getAuthIdentifier();

        //Validamos si el calendario donde se encuentra la clase es del usuario logueado
        $calendar = Calendar::find($clase->calendar_id);

        if ($id_user != $calendar->user_id){
            $errorMessage = 'Acceso no permitido a este calendario: ' . $args['id'];
            $error = new Error($errorMessage);
            throw $error;
        }

        //Error al eliminar el calendario
        $delete = $clase->delete();

        //Validamos que se borro de forma correcta
        if ($delete) {
            // El objeto Calendar se creó correctamente en la base de datos
            return $clase;

        } else {
            // Ocurrió un error al guardar el objeto Calendar en la base de datos
            $errorMessage = 'Error al eliminar clase: ' . $args['id'];
            $error = new Error($errorMessage);
            throw $error;
        }
    }
}
