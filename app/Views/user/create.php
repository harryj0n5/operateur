<h1>Ajouter utilisateur</h1>

<?php if (session()->getFlashdata('error')): ?>
    <p style="color: red;"><?= esc(session()->getFlashdata('error')) ?></p>
<?php endif; ?>

<form method="post" action="/users/store">

    <?= csrf_field() ?>

    <label>Téléphone</label>
    <input type="text" name="telephone" value="<?= esc(old('telephone')) ?>">


    <br>


    <label>Solde</label>
    <input type="number" name="solde" value="<?= esc(old('solde') ?? '0') ?>">


    <br>


    <label>Type utilisateur</label>

    <input type="number" name="type_user_id" value="<?= esc(old('type_user_id') ?? '2') ?>">


    <br>


    <button>
        Enregistrer
    </button>


</form>
