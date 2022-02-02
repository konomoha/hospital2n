<h1 class="text-center my-5"><?= $title; ?></h1>

<div class="container">

    <table class="table table-hover text-center">

        <thead><tr>

            <!-- On définit ici une boucle nous permettant de sélectionner dans la variable field la valeur contenue dans l'indice [Field], ce qui nous permet de récupérer le nom des champs de la table (à l'exception de l'id) -->

            <?php foreach($field as $key):?>
            
                <th class="p-3"><?=ucfirst($key["Field"]);?></th>
                
            <?php endforeach;?>

            <th>Action</th></tr>
            

        </thead>

        <tbody>

            <!-- On effectue ici une boucle foreach sur la variable $data afin d'afficher toutes les informations des rdv dans un tableau sur le navigateur -->

            <?php foreach ($data as $key=>$tab):?>

                <tr>
                    <?php foreach ($tab as $key=>$value):?>

                        <?php if($key != 'id'):?>
                        <td class="p-3"><?= $value;?></td>
                        <?php endif;?>

                    <?php endforeach;?>
                    <td>
                        <a href="?rdv=select&id=<?=$tab['id'];?>"><i class="bi bi-eye-fill lienprofil mx-1 text-dark"></i></a>

                        <a href="?rdv=delete&id=<?=$tab['id'];?>" onclick="return(confirm('Voulez-vous réellement supprimer ce rendez-vous?'));"><i class="bi bi-trash-fill lienprofil mx-1 text-dark"></i></a>
                    </td>
                </tr>

            <?php endforeach;?>

        </tbody>

    </table>
    
</div>

<div class="bloc_liens my-4">

    <p class="col-2 btn btn-outline-dark p-3"><a href="?rdv=add" class="text-dark">Créer un nouveau rendez-vous?</a></p>
    <p class="col-2 btn btn-outline-dark p-3"><a href="index.php" class="text-dark">retourner à l'accueil</a></p>

</div>