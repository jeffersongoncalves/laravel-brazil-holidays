<?php

return [

    /*
     * Treat federal "pontos facultativos" as holidays:
     * Carnival (Monday and Tuesday) and Corpus Christi.
     */
    'include_optional' => env('BRAZIL_HOLIDAYS_INCLUDE_OPTIONAL', false),

    /*
     * Extra fixed-date holidays (state or municipal), as 'MM-DD' => 'Name'.
     * Example: '09-20' => 'Revolução Farroupilha' (RS).
     */
    'extra' => [],

];
