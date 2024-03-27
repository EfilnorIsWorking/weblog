<?php if (count($errors) > 0) : ?>
<div class="message error validation_errors" >
    <?php foreach($errors as $erreur){
        echo "$erreur <br>";
    }
    ?>
</div>
<?php endif ?>