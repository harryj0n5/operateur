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

    <select name="type_user_id">
        <?php foreach ($typeUsers as $typeUser): ?>
            <option
                value="<?= esc($typeUser['id']) ?>"
                <?= (string) old('type_user_id') === (string) $typeUser['id'] ? 'selected' : '' ?>
            >
                <?= esc($typeUser['libelle']) ?>
            </option>
        <?php endforeach; ?>
    </select>


    <br>


    <button>
        Enregistrer
    </button>


</form>
