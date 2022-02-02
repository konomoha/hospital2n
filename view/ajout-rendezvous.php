<h1 class="text-center my-3"><?= ($rdv == 'add')? 'Ajout de rendez-vous' : 'Modification de rendez-vous';?></h1>

<?php
if($error)
{
   echo "<p class='bg bg-danger p-3 col-4 mx-auto mb-3 text-center'>$error</p>";
}

if(!$error && !empty($_POST))
{
    echo $success;
}
// echo "<pre>"; print_r($allId); echo "</pre>";
//echo "<pre>"; print_r($values); echo "</pre>"; //retourne toutes les valeurs des champs en cas d'update
//echo "<pre>"; print_r($field); echo "</pre>"; //retourne toutes les informations des différents champs de la table appointments

?>

<div class="container">

    <form action="" method="post" class="form-control">

        <?php foreach($field as $tab):?>

            <?php if($tab['Type'] == 'datetime'):
                $type = 'datetime-local';?>

                <?php elseif(substr($tab['Type'], 0, 3) == 'int'):
                    $type = 'number';?>

                <?php else:
                    $type = 'text';?>
            <?php endif;?>

            <?php if($type == 'number'):?>
                <div class="col-5 mx-auto my-2">

                    <label for="<?=$tab['Field'];?>" class='form-label'><?=$tab['Field'];?></label>

                    <select name="<?=$tab['Field'];?>" id="" class="form-control">
                        <option value="" disabled selected>Sélectionner</option>

                        <?php foreach($allId as $key=>$value):?>
                            <option value="<?=$value['id'];?>" <?= ($rdv == 'update' && $values[$tab['Field']] == $key)? 'selected': '';?>><?=$value['id'];?></option>
                        <?php endforeach;?>

                    </select>
                </div>
            <?php else:?>
                
                <div class="col-5 mx-auto my-2">

                    <label for="<?=$tab['Field'];?>" class='form-label'><?=$tab['Field'];?></label>

                    <input type="<?=$type;?>" name="<?=$tab['Field'];?>" id="<?=$tab['Field'];?>" class='form-control' value="<?= ($rdv == 'update')? $values[$tab['Field']] : '';?>">

                </div>
            <?php endif;?>

        <?php endforeach;?>

        <div class="col-5 mx-auto row">

            <button class="btn btn-dark col-5 mx-auto"><?=$title;?></button>  

        </div>       

    </form>

</div>

<div class="bloc_liens my-4">
    
    <p class="col-2 btn btn-outline-dark p-3"><a href="index.php" class="text-dark">retourner à l'accueil</a></p>
    
</div>

