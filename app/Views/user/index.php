<h1>Liste utilisateurs</h1>

<?php if (session()->getFlashdata('success')): ?>
    <p style="color: green;"><?= esc(session()->getFlashdata('success')) ?></p>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <p style="color: red;"><?= esc(session()->getFlashdata('error')) ?></p>
<?php endif; ?>

<a href="/users/create">
    Ajouter utilisateur
</a>


<table border="1">

    <tr>
        <th>ID</th>
        <th>Téléphone</th>
        <th>Solde</th>
        <th>Type</th>
        <th>Actions</th>
    </tr>


    <?php foreach ($users as $user): ?>

        <tr>

            <td>
                <?= esc($user['id']) ?>
            </td>

            <td>
                <?= esc($user['telephone']) ?>
            </td>

            <td>
                <?= esc($user['solde']) ?>
            </td>

            <td>
                <?= esc($user['type_user_id']) ?>
            </td>


            <td>

                <a href="/users/edit/<?= esc($user['id']) ?>">
                    Modifier
                </a>

                <form
                    method="post"
                    action="/users/delete/<?= esc($user['id']) ?>"
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
