<h1>Ajouter une configuration</h1>

<?php if (session()->getFlashdata('error')): ?>
    <p style="color: red;"><?= esc(session()->getFlashdata('error')) ?></p>
<?php endif; ?>

<form method="post" action="/configurations/store">

    <?= csrf_field() ?>

    <label>Préfixe</label>
    <input type="text" name="prefix" value="<?= esc(old('prefix')) ?>">


    <br>


    <button>
        Enregistrer
    </button>


</form>
