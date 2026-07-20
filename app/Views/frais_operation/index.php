<h1>Liste des frais d'opération</h1>

<?php if (session()->getFlashdata('success')): ?>
    <p style="color: green;"><?= esc(session()->getFlashdata('success')) ?></p>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <p style="color: red;"><?= esc(session()->getFlashdata('error')) ?></p>
<?php endif; ?>

<a href="/frais-operations/create">
    Ajouter un frais d'opération
</a>


<table border="1">

    <tr>
        <th>ID</th>
        <th>Type d'opération</th>
        <th>Montant min</th>
        <th>Montant max</th>
        <th>Frais</th>
        <th>Actions</th>
    </tr>


    <?php foreach ($fraisOperations as $fraisOperation): ?>

        <tr>

            <td>
                <?= esc($fraisOperation['id']) ?>
            </td>

            <td>
                <?= esc($fraisOperation['type_operation_libelle']) ?>
            </td>

            <td>
                <?= esc($fraisOperation['montant_min']) ?>
            </td>

            <td>
                <?= esc($fraisOperation['montant_max']) ?>
            </td>

            <td>
                <?= esc($fraisOperation['frais']) ?>
            </td>

            <td>

                <a href="/frais-operations/<?= esc($fraisOperation['id']) ?>/edit">
                    Modifier
                </a>

                <form
                    method="post"
                    action="/frais-operations/<?= esc($fraisOperation['id']) ?>/delete"
                    style="display:inline"
                    onsubmit="return confirm('Supprimer ?')"
                >
                    <?= csrf_field() ?>
                    <button type="submit">Supprimer</button>
                </form>

            </td>

        </tr>


    <?php endforeach; ?>


</table>
