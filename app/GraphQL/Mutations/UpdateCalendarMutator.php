<?php

namespace App\GraphQL\Mutations;

use App\Models\Calendar;
use GraphQL\Error\Error;
use Illuminate\Support\Facades\Auth;
use Mews\Purifier\Casts\CleanHtml;
use Mews\Purifier\Facades\Purifier;

final class UpdateCalendarMutator
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        //validamos los campos para que no lleven etiquestas html y evitar ataques xss
        $id = Purifier::clean($args["id"], CleanHtml::class);
        $name_calendar = Purifier::clean($args["name_calendar"], CleanHtml::class);
        $description_calendar = !empty($args["description_calendar"]) ? Purifier::clean($args["description_calendar"], CleanHtml::class) : '';
        $user_id = Purifier::clean($args["user_id"], CleanHtml::class);

        //buscamos el registro en base de datos para actualizar sus propiedades
        $calendar = Calendar::find($id);

        //Validamos que el registro pertenezca al usuario loqueado
        $id_user = Auth::user()->getAuthIdentifier();

        if ($id_user != $calendar->user_id){
            $errorMessage = 'Acceso no permitido a este calendario: ' . $args['id'];
            $error = new Error($errorMessage);
            throw $error;
        }

        //seteamos las pripiedades y en el caso description_calendar validamos que no venga vacio ya que no es obligatorio
        $calendar->name_calendar = $name_calendar;
        $calendar->description_calendar = empty($description_calendar) ? $calendar->name_calendar : $description_calendar;
        $calendar->user_id = $user_id;

        //actualizamos los datos
        $update = $calendar->update();

        if ($update) {
            // El objeto Calendar se creó correctamente en la base de datos
            return $calendar;

        } else {

            // Ocurrió un error al actualizar el objeto Calendar en la base de datos
            $errorMessage = 'Error al actualizar el calendario: ' . $args['id'];
            $error = new Error($errorMessage);
            throw $error;
        }

    }
}
