<h1>Liste des configurations</h1>

<?php if (session()->getFlashdata('success')): ?>
    <p style="color: green;"><?= esc(session()->getFlashdata('success')) ?></p>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <p style="color: red;"><?= esc(session()->getFlashdata('error')) ?></p>
<?php endif; ?>

<a href="/configurations/create">
    Ajouter une configuration
</a>


<table border="1">

    <tr>
        <th>ID</th>
        <th>Préfixe</th>
        <th>Actions</th>
    </tr>


    <?php foreach ($configurations as $configuration): ?>

        <tr>

            <td>
                <?= esc($configuration['id']) ?>
            </td>

            <td>
                <?= esc($configuration['prefix']) ?>
            </td>

            <td>

                <a href="/configurations/<?= esc($configuration['id']) ?>/edit">
                    Modifier
                </a>

                <form
                    method="post"
                    action="/configurations/<?= esc($configuration['id']) ?>/delete"
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
