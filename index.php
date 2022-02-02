<?php

require_once 'autoload.php';
    //On instancie un nouvel objet issu de controllerPatient.php. L'autoload s'execute dans controllerPatient et importe également la class patientRepository.php

    $controllerPatient = new Controller\ControllerPatient;
    $controllerAppointment = new Controller\ControllerAppointment;
    if(isset($_GET['op']))
    {
        $controllerPatient->handleRequest();
    }
    else
    {
        $controllerAppointment->handleRequest();
    }

    // echo '<pre>'; print_r($controllerPatient); echo '</pre>';