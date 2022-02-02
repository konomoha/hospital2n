

<h1 class="text-center my-5">Profil de <?="$data[firstname] $data[lastname]";?></h1>

<div class="container">

    <table class="table table-hover text-center">

        <thead><tr>

            <!-- On définit ici une boucle nous permettant de sélectionner dans la variable field la valeur contenue dans l'indice [Field], ce qui nous permet de récupérer le nom des champs de la table (à l'exception du la clé AI id) -->

            <?php foreach($field as $key):?>
            
                <th class="p-3"><?=ucfirst($key["Field"]);?></th>

            <?php endforeach;?><th>Modifier</th></tr>

        </thead>

        <tbody>

            <!-- On entre dans les cellules du tableau les différentes informations du patient sélectionné -->

                <tr>
                    <td><?=$data['lastname'];?></td>
                    <td><?=$data['firstname'];?></td>
                    <td><?=$data['birthdate'];?></td>
                    <td><?=$data['phone'];?></td>
                    <td><?=$data['mail'];?></td>
                    <td><a href="?op=update&id=<?=$data['id'];?>" class="text-dark"><i class="bi bi-pen-fill lienprofil"></i></a></td>
                </tr>
        </tbody>

    </table>

    <?php if($appointmentList):?>
    
        <h3 class="text-center my-5">Liste des rendez-vous de <?="$data[firstname] $data[lastname]";?></h3>

        <table class="table table-hover text-center">

            <thead><tr>

                <!-- On définit ici une boucle nous permettant de sélectionner dans la variable field la valeur contenue dans l'indice [Field], ce qui nous permet de récupérer le nom des champs de la table (à l'exception du la clé AI id) -->

                <?php foreach($appointmentField as $key):?>
                
                    <th class="p-3"><?=ucfirst($key["Field"]);?></th>

                <?php endforeach;?></tr>

            </thead>

            <tbody><tr>

                <?php foreach($appointmentList as $key=>$value):?>

                    <td><?=$value;?></td>
                    
                <?php endforeach;?></tr>

            </tbody>

        </table>
    <?php endif;?>
    
</div>

<div class="bloc_liens my-4">
    <p class="col-2 btn btn-outline-dark p-3"><a href="?op=list" class="text-dark">Retourner à la liste complète</a></p>
    <p class="col-2 btn btn-outline-dark p-3"><a href="index.php" class="text-dark">retourner à l'accueil</a></p>
</div>
