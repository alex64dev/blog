<form action="" method="post" enctype="multipart/form-data">
    <?= $form->input('name', 'Nom'); ?>
    <?= $form->input('slug', 'Url'); ?>

    <?= $category->getFile() ?>
    <?php if($category->getFile()){ ?>
        <div class='mb-3'>
            <img src="<?= $category->getRealPath() ?>" class="my-4" alt="categorie image" width="150px" height="auto">
            <a href="<?= $router->generate_url('admin_category_remove', ['id' => $category->getId()]) ?>"
                  style="display: inline"
                  class="btn btn-danger">
                Supprimer
            </a>
        </div>
    <?php }else{ ?>
        <?= $form->input('file', 'Logo', false); ?>
    <?php } ?>

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