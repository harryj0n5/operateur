<h1>Modifier un type d'opération</h1>

<?php if (session()->getFlashdata('error')): ?>
    <p style="color: red;"><?= esc(session()->getFlashdata('error')) ?></p>
<?php endif; ?>

<form method="post" action="/type-operations/<?= esc($typeOperation['id']) ?>/update">

    <?= csrf_field() ?>

    <label>Libellé</label>
    <input type="text" name="libelle" value="<?= esc(old('libelle') ?? $typeOperation['libelle']) ?>">


    <br>


    <button>
        Modifier
    </button>


</form>
