<?php

    Class Autoload
    {
        //Méthode appartenant uniquement à la class Autoload

        public static function include($className)
        {
            //Le nom du namespace (controller) me permet ici de rentrer dans le bon dossier et d'aller ensuite piocher dans le bon fichier en me servant du nom de la class.

            //Ici je me sers de la constante __DIR__ pour avoir un chemin absolu générique. Je concatène __DIR__ avec un slash et j'utilise la fonction str_replace qui permet de remplacer les anti-slashs par des slash dans $className. Ceci nous permet d'obtenir le bon require.

                require_once __DIR__ . '/' . str_replace('\\', '/', $className . '.php');
            
        }
    }

    //fonction spl_autoload_register() qui s'execute automatiquement lorsqu'elle voit passer le mot clé 'new'. On se sert de cette fonction pour envoyer automatiquement en argument de la fonction include() tout ce qui se trouve après le mot 'new'.
    spl_autoload_register(['Autoload', 'include']);
    