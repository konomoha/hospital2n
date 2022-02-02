

<h1 class="text-center my-5">Enregistrer un patient et un rendez-vous</h1>

<?php
    // echo '<pre>'; print_r($patientField); echo '</pre>';
    // echo '<pre>'; print_r($appointmentField); echo '</pre>';

    if($error)
    {
       echo "<p class='bg bg-danger p-3 col-4 mx-auto mb-3 text-center'>$error</p>";
    }

    // echo "<pre>"; print_r($field); echo "</pre>";

?>

<div class="container">

    <form action="" method="post" class="form-control">

        <?php foreach($patientField as $tab):?>

            <?php if($tab['Type'] == 'date'):?>

                <div class="col-5 mx-auto my-2">

                    <label for="<?=$tab['Field'];?>" class='form-label'><?=$tab['Field'];?></label>

                    <input type="date" name="<?=$tab['Field'];?>" id="<?=$tab['Field'];?>" class='form-control' >

                </div>

            <?php else:?>

                <div class="col-5 mx-auto my-2">

                    <label for="<?=$tab['Field'];?>" class='form-label'><?=$tab['Field'];?></label>
                    
                    <input type="text" name="<?=$tab['Field'];?>" id="<?=$tab['Field'];?>" class='form-control' >

                </div>
            <?php endif;?>

        <?php endforeach;?>

        <?php foreach($appointmentField as $tab):?>

            <?php if($tab['Field'] == 'dateHour'):?>
                
                <div class="col-5 mx-auto my-2">

                    <label for="<?=$tab['Field'];?>" class='form-label'>Date de Rendez-vous</label>

                    <input type="datetime-local" name="<?=$tab['Field'];?>" id="<?=$tab['Field'];?>" class='form-control'>

                </div>

            <?php endif;?>

        <?php endforeach;?>

         <div class="col-5 mx-auto row">

             <button class="btn btn-dark col-5 mx-auto"><?=$title;?></button>  

        </div>       
          
        
    </form>

    <div class="bloc_liens my-4">
    
    <p class="col-2 btn btn-outline-dark p-3"><a href="index.php" class="text-dark">retourner à l'accueil</a></p>
    
</div>

</div>