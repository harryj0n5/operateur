<h1>Modifier utilisateur</h1>

<?php if (session()->getFlashdata('error')): ?>
    <p style="color: red;"><?= esc(session()->getFlashdata('error')) ?></p>
<?php endif; ?>

<form method="post" action="/users/update/<?= esc($user['id']) ?>">

    <?= csrf_field() ?>

    <label>Téléphone</label>

    <input type="text" name="telephone" value="<?= esc(old('telephone') ?? $user['telephone']) ?>">


    <br>


    <label>Solde</label>

    <input type="number" name="solde" value="<?= esc(old('solde') ?? $user['solde']) ?>">


    <br>


    <label>Type utilisateur</label>

    <input type="number" name="type_user_id" value="<?= esc(old('type_user_id') ?? $user['type_user_id']) ?>">


    <br>


    <button>
        Modifier
    </button>


</form>
