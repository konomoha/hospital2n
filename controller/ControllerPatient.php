<?php

namespace Controller;

class ControllerPatient
{
    private $dbRepo; //Cette propriété nous peremttra de stocker un objet issu de la classe PatientRepository et d'avoir accès via des fonctions à toutes les données de la table patients.
    private $dbAppointmentRepo;//Cette propriété stockera un objet issu de AppointmentRepository
    private $nom;
    private $prenom;
    private $naissance;
    private $telephone;
    private $mail;
    private $error;
    private $dataForm;
    private $dataFormPatient;
    private $dataFormAppointment;
    

    public function __construct()
    {
      $this->dbRepo = new \Model\PatientRepository;
      $this->dbAppointmentRepo = new \Model\AppointmentRepository;
     
    }

    //###################################### FONCTION HANDLE REQUEST() #######################################

    public function handleRequest()
    {
        //On définit ici une fonction qui sera exécutée selon les données transmises dans l'URL
        // Si l'indice 'op' est défini dans l'url on stock sa valeur dans $op et on procède à la mise en place des conditions;
        $op = isset($_GET['op'])? $_GET['op'] : NULL;

        if($op == 'add' || $op == 'update')
        {
           
           $this->savePatient($op);
            
        }

        elseif($op == 'addboth')
        {
            $this->saveBoth();
        }

        elseif($op == 'select')
        {
            $this->patientProfil();
        }

        elseif($op == 'delete')
        {
            $this->delete();
        }

        elseif($op == 'list') 
        {
            
            $this->pagination();
           
        }
        elseif($op == $_GET['op'])
        {
            $this->displayLike();
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

    // #################################### FONCTION SAVEPATIENT() #########################

    public function savePatient($op)
    {
        $title = $op; //je défini une variable title qui contiendra soit la valeur "add" soit "update" en fonction de l'information transmise dans l'URL

        $success = "Enregistrement effectué!";

        //Structure ternaire: si un indice est envoyé dans l'URL, $id la récupérera. Dans le cas contraire, $id est null.
        $id = isset($_GET['id'])? $_GET['id'] : NULL;
        
        //On execute une requete de sélection en BDD, en cas de modification, afin de sélectionner les données de l'employé à modifier en BDD, en fonction de l'ID envoyé dans l'URL
        $values = ($op == 'update') ? $this->dbRepo->selectRepo($id) : '';

        if(!empty($_POST))
        {
            $this->setNom($_POST['lastname']);
            $this->setPrenom($_POST['firstname']);
            $this->setNaissance($_POST['birthdate']);
            $this->setTelephone($_POST['phone']);
            $this->setMail($_POST['mail']);
        
        
        if(!$this->error)
        {
            //dataForm récupère tous les getters et est envoyé comme argument de la méthode saveRepo
        
            $this->dataForm = [$this->getNom(), $this->getPrenom(), $this->getNaissance(), $this->getTelephone(), $this->getMail()];
            // echo "<pre>"; print_r($_POST); echo"</pre>";

            $this->dbRepo->saveRepo($this->dataForm);
            $this->redirect('index.php');
        }
        
        }

        $this->render('layout.php', 'ajout-patient.php', [
            'error'=>$this->error,
            'success'=>$success,
            'values'=>$values,
            'title'=>$title,
            'op' => $op,
            'field'=> $this->dbRepo->getFieldNames()
        ]);

    }

    // ################################### FONCTION SAVEBOTH()##########################################

    public function saveBoth()
    {
        if(!empty($_POST))
        {
            $this->setNom($_POST['lastname']);
            $this->setPrenom($_POST['firstname']);
            $this->setNaissance($_POST['birthdate']);
            $this->setTelephone($_POST['phone']);
            $this->setMail($_POST['mail']);
            $this->setDate($_POST['dateHour']);

            if(!$this->error)
            {
                //Nous stockons dans l'array formField tous les noms de champs nécessaires à l'insertion d'un nouveau patient. Cet array ira ensuite en argument de la fonction simultaneousSaving
                $formField = ['lastname', 'firstname', 'birthdate', 'phone', 'mail'];

                //Le second array dataFormPatient contient tous les getters nécessaires à l'insertion du patient. Il sera le second argument de la fonction.
                $this->dataFormPatient = [$this->getNom(), $this->getPrenom(), $this->getNaissance(), $this->getTelephone(), $this->getMail()];

                $this->dbRepo->simultaneousSaving($formField, $this->dataFormPatient);

                //Une fois l'insertion du patient effectuée, nous passons à l'enregistrement du rendez-vous. Etant donné qu'un patient ne peut s'enregistrer qu'avec une adresse mail unique, nous allons nous servir de cette restriction pour cibler l'id du Patient et procéder à l'enregistrement simultané du rendez-vous.

                $idPatient = $this->dbRepo->selectDataBy('id', 'mail', $_POST['mail']);
                

                $formField2 = ['dateHour', 'idPatients'];

                $this->dataFormAppointment = [$this->getDate(), $idPatient['id']];

                $this->dbAppointmentRepo->simultaneousSaving($formField2, $this->dataFormAppointment);

                $this->redirect('index.php');
            }
        }

        $this->render('layout.php', 'ajout-patient-rendez-vous.php', [
            'error'=>$this->error,
            'patientField'=> $this->dbRepo->getFieldNames(),
            'appointmentField'=> $this->dbAppointmentRepo->getFieldNames(),
            'title'=>'Envoyer'
        ]);

    }

    // ##################################### SETTERS / GETTERS PATIENTS ##################################

    public function setNom($nomForm)
    {
        if(!empty($nomForm))
        {
            if(is_string($nomForm) && !is_numeric($nomForm))
            {
                $this->nom = $nomForm;
            }
            else
            {
                $this->error .= "'". $nomForm."'". "n'est pas une chaine de caractères!<br>";
            }
            
        }

        else
        {
            $this->error .= "Veuillez renseigner le nom<br>";
        }
    }

    public function getNom()
    {
        return $this->nom;
    }

    public function setPrenom($prenomForm)
    {
        if(!empty($prenomForm))
        {
            if(is_string($prenomForm) && !is_numeric($prenomForm))
            {
                // echo "<pre>";print_r($prenomForm); echo"</pre>";
                $this->prenom = $prenomForm;
            }

            else
            {
                $this->error .= "'". $prenomForm."'". "n'est pas une chaine de caractères!<br>";
            }
            
        }

        else
        {
            $this->error .= "Veuillez renseigner le prénom<br>";
        }
    }

    public function getPrenom()
    {
        return $this->prenom;
    }

    public function setNaissance($naissanceForm)
    {
        if(!empty($naissanceForm))
        {
            $this->naissance = $naissanceForm;
        }

        else
        {
            $this->error .= "Veuillez sélectionner une date de naissance.<br>";
        }
    }

    public function getNaissance()
    {
        return $this->naissance;
    }

    public function setTelephone($telForm)
    {
        if(!empty($telForm))
        {
            if(is_numeric($telForm))
            {
                $this->telephone = $telForm;
            }

            else
            {
                $this->error .= "Veuillez entrer un numéro de téléphone valide!<br>";
            }
        }

        else
        {
            $this->error .= "Veuillez renseigner un numéro de téléphone.<br>";
        }
       
    }

    public function getTelephone()
    {
        return $this->telephone;
    }

    public function setMail($mailForm)
    {
        if(!empty($mailForm))
        {
           
            if(is_string($mailForm))
            {
                $verif = $this->dbRepo->dataVerif('mail', $mailForm);//Vérification de la présence du mail en bdd
                

                if($_GET['op'] == 'update')
                {
                    $id = isset($_GET['id'])? $_GET['id'] : NULL;
                    $patient = $this->dbRepo->selectRepo($id);
                    
                    if($mailForm == $patient['mail'] || $verif == 0) //En cas d'update, si la valeur entrée dans le champ est la même que celle du patient correspondant à l'id présent dans l'url, on insère en bdd. De même, si la valeur entrée dans le champ mail n'existe pas en bdd on insère également.
                    {
                        $this->mail = $mailForm; 
                    }
                    else//Dans le cas où le mail existe bien en bdd mais qu'il ne correspond pas à celui du patient, on renvoie une erreur.
                    {
                        $this->error .= "Un patient est déjà enregistré à l'adresse $mailForm<br>";
                    }
                
                }

                elseif($verif != 0) //En cas d'inscription, si le mail existe déjà en bdd et qu'il ne s'agit pas d'une update, on renvoie une erreur.
                {
                    $this->error .= "Un patient est déjà enregistré à l'adresse $mailForm<br>";
                }

                else
                {
                    $this->mail = $mailForm;
                }
                
            }

            else
            {
                $this->error .= "Veuillez entrer une addresse mail valide<br>";
            }
            
        }
        else
        {
            $this->error .= "Veuillez renseigner une adresse email<br>";
        }
    }

    public function getMail()
    {
        return $this->mail;
    }

     // ##################################### SETTERS / GETTERS APPOINTMENTS ##################################

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
 

    // ###################################### FONCTION PAGINATION()#######################################

    public function pagination()
    {
        
        $currentPage="";
        
        //Nous déterminons ici la page ou nous nous trouvons et stockons le résultat dans $currentPage
        if(isset($_GET['page']) && !empty($_GET['page']))
        {
            $currentPage = (int) strip_tags($_GET['page']);
        }
        
        else
        {
            $currentPage = 1;
        }

        //nbItem contiendra le nombre total de patients présent en bdd
        $nbItem = $this->dbRepo->dataCount('nbItem');

        //Nous choisissons un nombre d'articles affichés par pages
        $perPage = 10;

        //Le nombre de page sera déterminé en divisant le nombre d'éléments par le nombre d'articles à afficher par page. La fonction ceil nous permet d'arrondir le résultat à l'entier supérieur
        $pages = ceil($nbItem / $perPage);
        
        if($currentPage < 0 || $currentPage > $pages)
        {
            $this->redirect('index.php?op=list');
        }

        else
        {   
            //Nous calculons ensuite le 1er article de la page
            $first = ($currentPage * $perPage) - $perPage;

            $items = $this->dbRepo->dataPage($first, $perPage);
        }
        

        $this->render('layout.php', 'liste-patients.php', [
            'title' => 'Ensemble des patients',
            "currentPage"=>$currentPage,
            "nbItems"=>$nbItem,
            "perPage"=>$perPage,
            "pages"=>$pages,
            "first"=>$first,
            "data"=>$items,
            'field'=> $field = $this->dbRepo->getFieldNames(),
            
           
        ]);

    }

    // ########################################## FONCTION DISPLAYLIKE() #########################
    /*
        Méthode d'affichage spécifique basée sur un formulaire de recherche 
    */

    public function displayLike()
    {
        $search= false;

        if(isset($_GET['op']))
        {
            if(!empty($_GET['op']))
            {
               $datasearch = $this->dbRepo->searchLike('lastname');
                $search = true;
                $result = count($datasearch); 

            }
            else
            {
                $this->redirect('index.php?op=list');
            }
            
            $currentPage="";
        
            if(isset($_GET['page']) && !empty($_GET['page']))
            {
                $currentPage = (int) strip_tags($_GET['page']);
            }
            
            else
            {
                $currentPage = 1;
            }

            // //Nous ne pouvons pas faire de requête en bdd ici contrairement à la méthode pagination(). nbItem contiendra donc le nombre d'éléments présents dans l'array $datasearch.
            $nbItem = count($datasearch);

            // //Nous choisissons un nombre d'articles affichés par pages
            $perPage = 10;

            // //Le nombre de page sera déterminé en divisant le nombre d'éléments par le nombre d'articles à afficher par page. La fonction ceil nous permet d'arrondir le résultat à l'entier supérieur
            $pages = ceil($nbItem / $perPage);

            if(isset($_GET['page']) && $_GET['page'] > $pages)
            {
                $this->redirect('index.php?op=list');
            }

            else
            {
                $first = $perPage * ($currentPage - 1);
            }
            
            //Nous récupérons des parties précises de l'array $datasearch grâce à array_slice()
            $datasearch = array_slice($datasearch, $first, $perPage);    
            
        }

        //Pour une meilleure lisibilité du code de pagination sur le template liste-patients.php, nous conservons les mêmes noms de variables que celles de la fonction pagination(). 
        $this->render('layout.php', 'liste-patients.php', [
            'searchtitle' => 'Résultat de votre recherche',
            'field'=> $field = $this->dbRepo->getFieldNames(),
            'search'=>$search,
            'result' => $result,
            "currentPage"=>$currentPage,
            "nbItems"=>$nbItem,
            "perPage"=>$perPage,
            "pages"=>$pages,
            "first"=>$first,
            "data"=>$datasearch,      
        ]);
    }

    // ########################################### FONCTION PATIENTPROFIL()#######################
    
    //Fonction d'affichage d'un profil de patient

    public function patientProfil()
    {
        $id = isset($_GET['id'])? $_GET['id'] : NULL;
        $appointmentList="";
        $rdvid="";

        $appointmentData = $this->dbRepo->appointmentList('appointments', 'id', 'idPatients', $id);

        //Nous stockons ensuite les informations nécessaires sur les rendez-vous dans un array et nous le transmettons au template

        if(!empty($appointmentData))
        {
            $appointmentList = [$appointmentData['dateHour'], $appointmentData['idPatients']];
            $rdvid = $appointmentData['id'];
        }
        

        $this->render('layout.php', 'profil-patient.php', [
            'data' => $data = $this->dbRepo->selectRepo($id),
            'field'=> $field = $this->dbRepo->getFieldNames(),
            'appointmentField'=> $field = $this->dbAppointmentRepo->getFieldNames(),
            'appointmentList'=>$appointmentList,
            'rdvid'=>$rdvid
            

        ]);

    }

    // ###################################### FONCTION DELETE()##################################

    public function delete()
    {
        $id = isset($_GET['id'])? $_GET['id'] : NULL;
        $this->dbAppointmentRepo->deleteFromEntity('idPatients', $id);
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