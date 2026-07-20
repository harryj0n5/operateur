<h1>Liste des types d'opération</h1>

<?php if (session()->getFlashdata('success')): ?>
    <p style="color: green;"><?= esc(session()->getFlashdata('success')) ?></p>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <p style="color: red;"><?= esc(session()->getFlashdata('error')) ?></p>
<?php endif; ?>

<a href="/type-operations/create">
    Ajouter un type d'opération
</a>


<table border="1">

    <tr>
        <th>ID</th>
        <th>Libellé</th>
        <th>Actions</th>
    </tr>


    <?php foreach ($typeOperations as $typeOperation): ?>

        <tr>

            <td>
                <?= esc($typeOperation['id']) ?>
            </td>

            <td>
                <?= esc($typeOperation['libelle']) ?>
            </td>

            <td>

                <a href="/type-operations/<?= esc($typeOperation['id']) ?>/edit">
                    Modifier
                </a>

                <form
                    method="post"
                    action="/type-operations/<?= esc($typeOperation['id']) ?>/delete"
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
