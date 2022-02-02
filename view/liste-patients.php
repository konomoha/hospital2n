<?php
    //On fait appel ici à la variable data définie dans la méthode render() afin de pouvoir exploiter son contenu
    // echo '<pre>'; print_r($nbItems); echo '</pre>';
 
?>

<!-- Selon que la variable $search est définie à true ou false, le titre h1 affiché sera différent -->
<h1 class="text-center my-5"><?= (!empty($search))? $searchtitle : $title;?></h1>

<!-- Si le résultat de la recherche est nul, on affiche un message d'erreur et on propose une nouvelle recherche -->
<?php if (isset($result) && $result == false):?>

    <h2 class="text-center fst-italic">"<?= $_GET['op'];?>" ne figure pas parmi les patients enregistrés</h2>
    <p class="text-center fst-italic">(La recherche s'effectue par nom de famille (lastname))!</p>

    <div class="col-5 mx-auto mt-3 text-center">
        <form action="" method="get" >

            <input type="search" name="op" placeholder="<?= (!empty($search))? 'Nouvelle recherche' : 'Recherche';?>">
            <input type="submit" name="envoyer">

        </form>
        <div class="bloc_liens my-4">
        <p class="col-4 btn btn-outline-dark p-3"><a href="?op=list" class="text-dark">Retourner à la liste complète</a></p>
            <p class="col-4 btn btn-outline-dark p-3"><a href="index.php" class="text-dark">retourner à l'accueil</a></p>
        </div>
    </div>


    

<?php else:?>

<div class="container">

    <div>
        <form action="" method="get" >

            <input type="search" name="op" placeholder="<?= (!empty($search))? 'Nouvelle recherche' : 'Rechercher Par nom de famille';?>">
            <input type="submit" name="envoyer">

        </form>
    </div>

    <?php if(!empty($search)):?>

        <div class="bloc_liens my-4">
        <p class="col-4 btn btn-outline-dark p-3"><a href="?op=list" class="text-dark">Retourner à la liste complète</a></p>
            <p class="col-4 btn btn-outline-dark p-3"><a href="index.php" class="text-dark">retourner à l'accueil</a></p>
        </div>

    <?php else:?>

        <p class="my-4 col-2 btn btn-outline-dark p-3 liens"><a href="index.php" class="text-dark">retourner à l'accueil</a></p>

    <?php endif;?>

    <table class="table table-hover text-center">

        <thead><tr>

            <!-- On définit ici une boucle nous permettant de sélectionner dans la variable field la valeur contenue dans l'indice [Field], ce qui nous permet de récupérer le nom des champs de la table (à l'exception du la clé AI id) -->

            <?php foreach($field as $key):?>
            
                <th class="p-3"><?=ucfirst($key["Field"]);?></th>
                
            <?php endforeach;?>

            <th>Profil</th></tr>
            
        </thead>

        <tbody>

            <!-- On effectue ici une boucle foreach conditionnée par une structure ternaire: si la variable $search est définie à false, la boucle foreach s'effectuera sur l'array multi $data et tous les patients seront affichés. Si l'on effectue une recherche via le formulaire, la variable $search sera définie à true, et la boucle foreach portera sur l'array $data -->

            <?php foreach ($data as $key=>$tab):?>

                <tr>
                    
                    <?php foreach ($tab as $key=>$value):?>

                        <?php if($key != 'id'):?>
                        <td class="p-3"><?= $value;?></td>
                        <?php endif;?>

                    <?php endforeach;?>

                    <td>
                        <a href="?op=select&id=<?=$tab['id'];?>"><i class="bi bi-eye-fill lienprofil mx-1 text-dark"></i></a>

                        <a href="?op=delete&id=<?=$tab['id'];?>" onclick="return(confirm('Voulez-vous réellement supprimer le patient n°<?=$tab['id'];?> et tous ses rendez-vous?'));"><i class="bi bi-trash-fill lienprofil mx-1 text-dark"></i></a>
                    </td>

                </tr>

            <?php endforeach;?>

        </tbody>

    </table>
    
        <nav >

            <ul class="pagination">

                
                <li class="page-item <?=($currentPage == 1) ? "disabled" : "" ?>">
                    
                    <!-- Structure ternaire: si une recherche a été effectuée, l'indice op dans l'URL aura pour valeur le nom recherché. Sinon la valeur sera 'list' -->
                    <a href="./?op=<?=(!empty($result))? $_GET['op']:'list';?>&page=<?= $currentPage - 1 ?>" class="page-link">Précédente</a>

                </li>

                <?php for($page = 1; $page <= $pages; $page++): ?>

                    <li class="page-item <?= ($currentPage == $page) ? "active" : "" ?>">

                        <a href="./?op=<?=(!empty($result))? $_GET['op']:'list';?>&page=<?= $page ?>" class="page-link"><?= $page ?></a>

                    </li>

                <?php endfor ?>

                
                    <li class="page-item <?=($currentPage == $pages) ? "disabled" : ""; ?>">

                        <a href="./?op=<?=(!empty($result))? $_GET['op']:'list';?>&page=<?= $currentPage + 1 ?>" class="page-link">Suivante</a>

                    </li>

            </ul>

        </nav>
 
</div>

        <?php endif;?>  

