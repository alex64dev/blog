<form action="" method="post">
    <?= $form->input('name', 'Titre'); ?>
    <?= $form->input('slug', 'Url'); ?>
    <?= $form->textArea('content', 'Contenu', false); ?>
    <?= $form->input('created_at', 'Date de création'); ?>

    <button class="btn btn-primary">
        <?php if($post->getId() !== null )
        {
            echo "Èditer";
        }else{
            echo "Ajouter";
        }?>
    </button>
    <a href="<?= $router->generate_url('admin_posts') ?>" class="btn btn-info">Retour</a>
</form>