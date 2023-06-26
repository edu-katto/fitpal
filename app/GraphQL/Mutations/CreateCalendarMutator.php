<?php

namespace App\GraphQL\Mutations;

use App\Models\Calendar;
use GraphQL\Error\Error;
use Mews\Purifier\Casts\CleanHtml;
use Mews\Purifier\Facades\Purifier;

final class CreateCalendarMutator
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {

        //validamos los campos para que no lleven etiquestas html y evitar ataques xss
        $name_calendar = Purifier::clean($args["name_calendar"], CleanHtml::class);
        $description_calendar = Purifier::clean($args["description_calendar"], CleanHtml::class);
        $user_id = Purifier::clean($args["user_id"], CleanHtml::class);

        //Registramos los datos
        $calendar = Calendar::create([
            'name_calendar' => $name_calendar,
            'description_calendar' => $description_calendar,
            'user_id' => $user_id
        ]);

        //Comprobamos que el registro sea exitoso
        if ($calendar) {
            // El objeto Calendar se creó correctamente en la base de datos
            return $calendar;

        } else {

            // Ocurrió un error al guardar el objeto Calendar en la base de datos
            $errorMessage = 'Error al crear el calendario';
            $error = new Error($errorMessage);
            throw $error;
            // Ocurrió un error al eliminar el objeto Calendar en la base de datos
        }
    }
}
