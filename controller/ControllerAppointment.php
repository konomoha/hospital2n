<?php

namespace Controller;

use Model\PatientRepository;

class ControllerAppointment
{
    private $dbRepo;
    private $dbPatientRepo;
    private $date;
    private $patient;
    private $error;
    private $dataForm;
    

    public function __construct()
    {
      $this->dbRepo = new \Model\AppointmentRepository;
      $this->dbPatientRepo = new \Model\PatientRepository;
    }

    //###################################### FONCTION HANDLE REQUEST() #######################################

    public function handleRequest()
    {
        //On définit ici une fonction qui sera exécutée selon les données transmises dans l'URL
        // Si l'indice 'rdv' est défini dans l'url on stock sa valeur dans $rdv et on procède à la mise en place des conditions;
        $rdv = isset($_GET['rdv'])? $_GET['rdv'] : NULL;

        if($rdv == 'add' || $rdv == 'update')
        {
           
           $this->saveAppointment($rdv);
            
        }

        elseif($rdv == 'select')
        {
            $this->appointmentData();
        }

        elseif($rdv == 'delete')
        {
            $this->delete();
        }

        elseif($rdv == 'list') 
        {
            $this->displayAll();
           
        }

        else
        {
            $this->render('layout.php', 'home.php', [
                'title' => 'Accueil'
               
            ]);
        }
    }

    // ######################################## FONCTION RENDER() ############################################

    //On construit ici une fonction render() qui nous permettra d'envoyer sur le navigateur toutes les données que l'on souhaite.
    public function render($layout, $template, $parameters = [])
    {
        //La méthode extract() transforme et extrait les données d'un tableau en variables que nous pourrons appeler sur les différents templates 
        extract($parameters);

        //Début de la mise en mémoire des valeurs
        ob_start();

        require "view/$template";

        $content = ob_get_clean(); //Cette fonction permet de faire un copier-coller de ce qu'on récupère via le require et de le stocker dans la variable $content

        ob_start();

        require "view/$layout"; //on appelle ici le gabarit de base (base.php)

        return ob_end_flush(); // fin de la mise en mémoire et libération sur le navigateur de toutes les valeurs stockées.

    }

    // #################################### FONCTION SAVEAPPOINTMENT() #########################

    public function saveAppointment($rdv)
    {
        $title = $rdv; //je défini une variable title qui contiendra soit la valeur "add" soit "update" en fonction de l'information transmise dans l'URL

        $success = "Enregistrement effectué!";

        $allId = $this->dbPatientRepo->selectBy('id');
        //Structure ternaire: si un indice est envoyé dans l'URL, $id la récupérera. Dans le cas contraire, $id est null.
        $id = isset($_GET['id'])? $_GET['id'] : NULL;
        
        //On execute une requete de sélection en BDD, en cas de modification, afin de sélectionner les données de l'employé à modifier en BDD, en fonction de l'ID envoyé dans l'URL
        $values = ($rdv == 'update') ? $this->dbRepo->selectRepo($id) : '';

        if(!empty($_POST))
        {
            $this->setDate($_POST['dateHour']);
            $this->setPatient($_POST['idPatients']);

        if(!$this->error)
        {
            //dataForm récupère tous les getters et est envoyé comme argument de la méthode saveRepo

            $this->dataForm = [$this->getDate(), $this->getPatient()];
            // echo "<pre>"; print_r($_POST); echo"</pre>";

            $this->dbRepo->saveRepo($this->dataForm);
            $this->redirect('index.php?rdv=list');
        }
        
        }

        $this->render('layout.php', 'ajout-rendezvous.php', [
            'error'=>$this->error,
            'success'=>$success,
            'values'=>$values,
            'title'=>$title,
            'rdv' => $rdv,
            'field'=> $this->dbRepo->getFieldNames(),
            'allId'=> $allId
            
        ]);

    }

    // ##################################### SETTERS / GETTERS ##########################################

    public function setDate($dateForm)
    {
        if(!empty($dateForm))
        {
            $this->date = $dateForm;
        }

        else
        {
            $this->error .= "Veuillez sélectionner une date de rendez-vous<br>";
        }
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setPatient($patientForm)
    {
        if(!empty($patientForm))
        {
                $this->patient = $patientForm;
        }

        else
        {
            $this->error .= "Veuillez sélectionner un patient<br>";
        }
     
       
    }

    public function getPatient()
    {
        return $this->patient;
    }

    
    // ########################################## FONCTION DISPLAYALL() #########################
    /*
        Méthode d'affichage  de tous les rendez-vous sur le template
    */
    public function displayAll()
    {
        // $dataAll = $this->dbRepo->selectAll();

        // echo '<pre>'; print_r($dataAll); echo '</pre>';

        //On utilise ici la méthode render() afin de libérer sur le template displayall.php les données requises
        $this->render('layout.php', 'liste-rendezvous.php', [
            'title' => 'Ensemble des rendez-vous',
            'data' => $dataAll = $this->dbRepo->selectAll(),
            'field'=> $field = $this->dbRepo->getFieldNames()
        ]);
        
    }

    // ########################################### FONCTION APPOINTMENTDATA()#######################
    
    //Fonction d'affichage détaillé d'un rendez-vous

    public function appointmentData()
    {
        $id = isset($_GET['id'])? $_GET['id'] : NULL;
        $data = $this->dbRepo->selectRepo($id);
        $idPatient = $data['idPatients'];
        $dataPatient = $this->dbPatientRepo->selectRepo($idPatient);

        $this->render('layout.php', 'rendezvous.php', [
            'data' => $data,
            'field'=> $field = $this->dbRepo->getFieldNames(),
            'dataPatient' => $dataPatient

        ]);

    }

    // ######################################## FONCTION DELETE()#####################################

    public function delete()
    {
        $id = isset($_GET['id'])? $_GET['id'] : NULL;
        $this->dbRepo->deleteFromEntity('id', $id);
        $this->redirect('index.php');

    }


    // ##################################### FONCTION REDIRECT() ###################################
   
    //En fonction de l'information transmise dans l'URL, on utilisera cette fonction pour rediriger l'internaute sur la page voulue
    public function redirect($url)
    {
        header("location: $url");
    }
}