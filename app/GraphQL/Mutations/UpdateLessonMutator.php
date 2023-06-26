<?php

namespace App\GraphQL\Mutations;

use App\Models\Calendar;
use App\Models\Lesson;
use GraphQL\Error\Error;
use Illuminate\Support\Facades\Auth;
use Mews\Purifier\Casts\CleanHtml;
use Mews\Purifier\Facades\Purifier;

final class UpdateLessonMutator
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        //validamos los campos para que no lleven etiquestas html y evitar ataques xss
        $id = Purifier::clean($args["id"], CleanHtml::class);
        $name_lesson = Purifier::clean($args["name_lesson"], CleanHtml::class);
        $description_lesson = !empty($args["description_lesson"]) ? Purifier::clean($args["description_lesson"], CleanHtml::class) : '';
        $calendar_id = Purifier::clean($args["calendar_id"], CleanHtml::class);
        $date_start_lesson = Purifier::clean($args["date_start_lesson"], CleanHtml::class);
        $date_end_lesson = Purifier::clean($args["date_end_lesson"], CleanHtml::class);
        $duration_lesson = Purifier::clean($args["duration_lesson"], CleanHtml::class);

        //buscamos el registro en base de datos para actualizar sus propiedades
        $clase = Lesson::find($id);

        //Validamos que el registro pertenezca al usuario loqueado
        $id_user = Auth::user()->getAuthIdentifier();

        //Validamos si el calendario donde se encuentra la clase es del usuario logueado
        $calendar = Calendar::find($clase->calendar_id);

        if ($id_user != $calendar->user_id){
            $errorMessage = 'Acceso no permitido a este calendario: ' . $args['id'];
            $error = new Error($errorMessage);
            throw $error;
        }

        //seteamos las pripiedades y en el caso description_lesson validamos que no venga vacio ya que no es obligatorio
        $clase->name_lesson = $name_lesson;
        $clase->description_lesson = empty($description_lesson) ? $clase->description_lesson : $description_lesson;
        $clase->calendar_id = $calendar_id;
        $clase->date_start_lesson = $date_start_lesson;
        $clase->date_end_lesson = $date_end_lesson;
        $clase->duration_lesson = $duration_lesson;

        //actualizamos los datos
        $update = $clase->update();

        if ($update) {
            // El objeto Calendar se creó correctamente en la base de datos
            return $clase;

        } else {

            // Ocurrió un error al guardar el objeto Calendar en la base de datos
            $errorMessage = 'Error al actualizar clase: ' . $args['id'];
            $error = new Error($errorMessage);
            throw $error;
        }


    }
}
