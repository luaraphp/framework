<form 
    <?= $method ?> 
    <?= $action ?>
>
    <?php foreach($fields as $name => $attributes) { ?>
        <?php if(is_array($attributes)) { ?>
            <?= $attributes['label'] ?>: <input 
                name="<?= $name ?>"
                type="<?= $attributes['type'] ?>"
            />
            <?php if($attributes['nextLine'] == true) { ?>
                <br>
            <?php } ?>  
        <?php } ?>
    <?php } ?>
</form>