<h1>Modifier une configuration</h1>

<?php if (session()->getFlashdata('error')): ?>
    <p style="color: red;"><?= esc(session()->getFlashdata('error')) ?></p>
<?php endif; ?>

<form method="post" action="/configurations/<?= esc($configuration['id']) ?>/update">

    <?= csrf_field() ?>

    <label>Préfixe</label>
    <input type="text" name="prefix" value="<?= esc(old('prefix') ?? $configuration['prefix']) ?>">


    <br>


    <button>
        Modifier
    </button>


</form>
