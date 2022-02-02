<?php
namespace Model;

Class AppointmentRepository
{
    private $db; // ici, nous stockons un objet PDO. La variable est ici en private pour éviter que l'on puisse la modifier à l'extérieur de la class. 

    public $table; //cette variable contiendra la table SQL souhaitée


    // ######################################### FONCTION GETDB() #####################################

    //Cette méthode assurera la connexion à la BDD et retourne un objet PDO qui sera stocké dans la variable private $db
    public function getDb()
    {
        //On met d'abord en place une condition. Si $db est null cela veut que la connexion n'a pas été construite. On en génère donc une.
        if(!$this->db)
        {
            //On tente d'ici d'accéder aux données de la bdd présent dans la fichier xml via un bloc d'essai try - catch permettrant de centraliser et personnaliser les erreurs.

            try
            {
                //simplexml_load_file() permet de charger le fichier config.xml. La variable $xml devient alors un objet SimpleXMLElement qui contient toutes les information du fichier config.xml.
                $xml = simplexml_load_file("app/config.xml");

                // echo '<pre>'; print_r($xml); echo '</pre>';

                //On pioche dans l'array table la table désirée et on la stock dans la propriété $table de la class AppointmentRepository;

                //Cette boucle permet de faciliter la recherche d'une table spécifique. Il suffit de modifier la valeur de $tab dans la condition if pour stocker la bonne table dans $this->table.
                foreach($xml->table as $key=>$tab)
                {
                    if($tab == 'appointments')
                    {
                        $this->table = $tab;
                    }
                }
                


               //Tentative de connexion à la base de donnée hospitale2n;
               try
               {
                   //On entre les coordonnées de la bdd de manière générique en se servant du fichier config.xml. Cela nous permettra de ne pas avoir à effectuer des modifications directement dans ce Repository. Il suffira d'aller dans le fichier config.xml;

                    $this->db = new \PDO("mysql:host=" . $xml->host . ";dbname=" . $xml->db, $xml->user, $xml->password, [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);

                    
               }

               catch(\PDOException $e)
               {
                    echo "Message : " . $e->getMessage();
               }

            }

            catch(\Exception $e)
            {
                echo "Message : " . $e->getMessage();
            }

        }

        //Si $db contient déjà un objet PDO, on le retourne
        return $this->db;
    }


    // ############################### FONCTION SAVEREPO() ##################################

    public function saveRepo($dataForm)
    {
    
        if(!empty($_POST))
        {
            //si un id est passé dans l'URL on le stock dans la variable $id. REPLACE executera une requête de modification. dans le cas contraire, ce sera une requête d'insertion.
            $id = isset($_GET['id']) ? $_GET['id'] : 'NULL';

            //Nous tentons ici d'automatiser la requête d'insertion et de modification afin de pouvoir réutiliser ce code pour d'autres projets.

            //implode() nous permet de transformer les éléments l'array $_POST en une chaîne de caractères. Nous choisissons ici le séparateur ',' afin que la requête soit valide. Nous utilisons comme second argument de la méthode implode() la fonction array_keys() qui nous permet de récupérer tous les indices du tableau $_POST.

            $insert = $this->getDb()->prepare("REPLACE INTO ". $this->table. " (id, ". implode(',', array_keys($_POST)). ") VALUES (". $id. ", :". implode(', :', array_keys($_POST)). ")");

            //La méthode que nous construisons ici attend en argument un array ($dataForm). Celui-ci contiendra tous les getters() issus du formulaire d'enregistrement. Nous nous servons ensuite de cet array pour effectuer des bindvalue de manière dynamique via une boucle foreach et un compteur $e. Encore une fois, l'idée est de rendre ce code générique et réutilisable dans une certaine mesure.

            $e = 0;

            foreach(array_keys($_POST) as $key)
            {
                $insert->bindValue(":$key", $dataForm[$e], \PDO::PARAM_STR);
                $e++; 
            }

            $insert->execute();

        }
    }

    //#################################### FONCTION SIMULTANEOUS SAVING()####################################

    public function simultaneousSaving($formField, $dataForm)
    {
        if(!empty($_POST))
        {
            $insert = $this->getDb()->prepare("REPLACE INTO ". $this->table. " (". implode(',', $formField). ") VALUES (:". implode(', :', $formField). ")");

            for($i=0; $i< count($formField); $i++)
            {
                $insert->bindValue(":$formField[$i]", $dataForm[$i], \PDO::PARAM_STR);
            }

            $insert->execute();
        }
    }

    //######################################### FONCTION GET FIELD NAMES() ################################

    public function getFieldNames()
    {
        $r = $this->getDb()->query("DESC " . $this->table);

        $data = $r->fetchAll(\PDO::FETCH_ASSOC);
        
        return array_splice($data,1); //suppression de la colonne 'id' pour qu'elle ne s'affiche pas sur le template displayall.php
    }


    // ######################################### FONCTION SELECTALL()###############################

    //On sélectionne via cette méthode l'ensemble de la table dans la bdd.
    public function selectAll()
    {
        $q = $this->getDb()->query("SELECT * FROM " . $this->table);

        //On récupère toutes les données via la méthode fetchAll() de l'objet PDOStatement $q
        $allData = $q->fetchAll(\PDO::FETCH_ASSOC);

        return $allData;
    }

    // ######################################## FONCTION SELECTREPO()######################

    //On sélectionne via cette méthode toutes les données concernant un rdv spécifique
    public function selectRepo(int $id)
    {
        $q = $this->getDb()->query("SELECT * FROM " . $this->table . " WHERE id". '=' . $id);

        //Il ne s'agit que d'un seul rdv, un fetch simple est suffisant.
        $data = $q->fetch(\PDO::FETCH_ASSOC);

        return $data;

    }

    // ######################################## FONCTION DELETE()################

    //Nous construison ici une méthode visant à supprimer de la bdd la lilgne sélectionnée

    public function deleteFromEntity($field, int $id)
    {   
        $q= $this->getDb()->query("DELETE FROM ". $this->table ." WHERE $field" . "=$id");
    }


   
}