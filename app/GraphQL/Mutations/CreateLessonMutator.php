<?php

namespace App\GraphQL\Mutations;

use App\Models\Lesson;
use GraphQL\Error\Error;
use Mews\Purifier\Casts\CleanHtml;
use Mews\Purifier\Facades\Purifier;

final class CreateLessonMutator
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        //Validamos los campos para que no lleven etiquestas html y evitar ataques xss
        $name_lesson = Purifier::clean($args["name_lesson"], CleanHtml::class);
        $description_lesson = Purifier::clean($args["description_lesson"], CleanHtml::class);
        $calendar_id = Purifier::clean($args["calendar_id"], CleanHtml::class);
        $date_start_lesson = Purifier::clean($args["date_start_lesson"], CleanHtml::class);
        $date_end_lesson = Purifier::clean($args["date_end_lesson"], CleanHtml::class);
        $duration_lesson = Purifier::clean($args["duration_lesson"], CleanHtml::class);

        //Registramos los datos
        $lesson = Lesson::create([
            'name_lesson' => $name_lesson,
            'description_lesson' => $description_lesson,
            'calendar_id' => $calendar_id,
            'date_start_lesson' => $date_start_lesson,
            'date_end_lesson' => $date_end_lesson,
            'duration_lesson' => $duration_lesson
        ]);

        //Comprobamos que el registro sea exitoso
        if ($lesson) {
            // El objeto Calendar se creó correctamente en la base de datos
            return $lesson;

        } else {

            // Ocurrió un error al guardar el objeto Calendar en la base de datos
            $errorMessage = 'Error al crear clase';
            $error = new Error($errorMessage);
            throw $error;
            // Ocurrió un error al eliminar el objeto Calendar en la base de datos
        }
    }
}
