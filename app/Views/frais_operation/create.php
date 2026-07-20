<h1>Ajouter un frais d'opération</h1>

<?php if (session()->getFlashdata('error')): ?>
    <p style="color: red;"><?= esc(session()->getFlashdata('error')) ?></p>
<?php endif; ?>

<form method="post" action="/frais-operations/store">

    <?= csrf_field() ?>

    <label>Type d'opération</label>

    <select name="type_operation_id">
        <?php foreach ($typeOperations as $typeOperation): ?>
            <option
                value="<?= esc($typeOperation['id']) ?>"
                <?= (string) old('type_operation_id') === (string) $typeOperation['id'] ? 'selected' : '' ?>
            >
                <?= esc($typeOperation['libelle']) ?>
            </option>
        <?php endforeach; ?>
    </select>


    <br>


    <label>Montant min</label>
    <input type="number" step="any" name="montant_min" value="<?= esc(old('montant_min')) ?>">


    <br>


    <label>Montant max</label>
    <input type="number" step="any" name="montant_max" value="<?= esc(old('montant_max')) ?>">


    <br>


    <label>Frais</label>
    <input type="number" step="any" name="frais" value="<?= esc(old('frais')) ?>">


    <br>


    <button>
        Enregistrer
    </button>


</form>
