<h1 class="text-center my-3"><?= ($op == 'add')? "Ajout de Patient": "Modification du profil de $values[firstname] $values[lastname]";?></h1>

<?php

    if($error)
    {
       echo "<p class='bg bg-danger p-3 col-4 mx-auto mb-3 text-center'>$error</p>";
    }

    if(!$error && !empty($_POST))
    {
        echo $success;
    }

    // echo "<pre>"; print_r($field); echo "</pre>";

?>
<div class="container">

    <form action="" method="post" class="form-control">

        <?php foreach($field as $tab):?>

            <?php if($tab['Type'] == 'date'):?>

                <div class="col-5 mx-auto my-2">

                    <label for="<?=$tab['Field'];?>" class='form-label'><?=$tab['Field'];?></label>

                    <input type="date" name="<?=$tab['Field'];?>" id="<?=$tab['Field'];?>" class='form-control' value="<?= ($op == 'update')? $values[$tab['Field']] : '';?>">

                </div>

            <?php else:?>

                <div class="col-5 mx-auto my-2">

                    <label for="<?=$tab['Field'];?>" class='form-label'><?=$tab['Field'];?></label>
                    
                    <input type="text" name="<?=$tab['Field'];?>" id="<?=$tab['Field'];?>" class='form-control' value="<?= ($op == 'update')? $values[$tab['Field']] : '';?>" >

                </div>
            <?php endif;?>

        <?php endforeach;?>

         <div class="col-5 mx-auto row">

             <button class="btn btn-dark col-5 mx-auto"><?=$title;?></button>  

        </div>       
          
        
    </form>

</div>

<div class="bloc_liens my-4">
    <?php if($op == 'update'):?>
        <p class="col-2 btn btn-outline-dark p-3"><a href="?op=select&id=<?=$values['id'];?>" class="text-dark">retourner au profil</a></p>
    <?php endif;?>

    <p class="col-2 btn btn-outline-dark p-3"><a href="index.php" class="text-dark">retourner à l'accueil</a></p>
</div>
