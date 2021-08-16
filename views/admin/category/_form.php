<form action="" method="post">
    <?= $form->input('name', 'Nom'); ?>
    <?= $form->input('slug', 'Url'); ?>

    <button class="btn btn-primary">
        <?php if($category->getId() !== null )
        {
            echo "Éditer";
        }else{
            echo "Ajouter";
        }?>
    </button>
    <a href="<?= $router->generate_url('admin_categories') ?>" class="btn btn-info">Retour</a>
</form>