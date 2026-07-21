<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vola - Types d'operation</title>
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/bootstrap/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>

<div class="admin-shell">

    <?= view('partials/sidebar', ['active' => 'type-operations']) ?>

    <main class="admin-main">

        <div class="admin-topline">
            <h1>Types d'operation</h1>
            <a href="/type-operations/create" class="btn btn-secondary btn-sm"><i class="bi bi-plus"></i> Ajouter un
                type</a>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert success" style="margin:0 0 16px;"><i
                        class="bi bi-circle-check"></i> <?= esc(session()->getFlashdata('success')) ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert error" style="margin:0 0 16px;"><i
                        class="bi bi-alert-circle"></i> <?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>

        <div class="table-card">
            <table class="data">
                <tr>
                    <th>ID</th>
                    <th>Libelle</th>
                    <th>Actions</th>
                </tr>

                <?php foreach ($typeOperations as $typeOperation): ?>
                    <tr>
                        <td>#<?= esc($typeOperation['id']) ?></td>
                        <td><?= esc($typeOperation['libelle']) ?></td>
                        <td>
                            <div class="row-actions">
                                <a href="/type-operations/<?= esc($typeOperation['id']) ?>/edit"
                                   class="btn btn-ghost btn-sm"><i class="ti ti-edit"></i> Modifier</a>
                                <form method="post" action="/type-operations/<?= esc($typeOperation['id']) ?>/delete"
                                      onsubmit="return confirm('Supprimer ?')">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-danger btn-sm"><i class="ti ti-trash"></i>
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>

    </main>

</div>

</body>
</html>


