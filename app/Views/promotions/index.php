<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vola - Promotions</title>
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/bootstrap/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>

<div class="admin-shell">

    <?= view('partials/sidebar', ['active' => 'promotions']) ?>

    <main class="admin-main">

        <div class="admin-topline">
            <h1>Promotions</h1>
            <a href="<?= site_url('configurations/create') ?>" class="btn btn-secondary btn-sm">
                <i class="bi bi-plus-lg"></i> Ajouter une promotion
            </a>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert success" style="margin:0 0 16px;">
                <i class="bi bi-check-circle"></i> <?= esc(session()->getFlashdata('success')) ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert error" style="margin:0 0 16px;">
                <i class="bi bi-exclamation-circle"></i> <?= esc(session()->getFlashdata('error')) ?>
            </div>
        <?php endif; ?>

        <div class="table-card">
            <table class="data">
                <tr>
                    <th>ID</th>
                    <th>Pourcentage</th>
                    <th>Operateur</th>
                    <th>Actions</th>
                </tr>

                <?php foreach ($promotions as $promotion): ?>
                    <tr>
                        <td>#<?= esc($promotion['id']) ?></td>
                        <td>
                            <?= esc($promotion['pourcentage']) ?>
                        </td>
                        <td><?= esc($promotion['operateur_libelle']) ?></td>
                        <td>
                            <div class="row-actions">
                                <a href="<?= site_url('promotions/' . $promotion['id'] . '/edit') ?>"
                                   class="btn btn-ghost btn-sm">
                                    <i class="bi bi-pencil"></i> Modifier
                                </a>
                                <form method="post"
                                      action="<?= site_url('promotions/' . $promotion['id'] . '/delete') ?>"
                                      onsubmit="return confirm('Supprimer ?')">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash"></i> Supprimer
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