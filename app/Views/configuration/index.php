<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vola - Configurations</title>
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>

<div class="admin-shell">

    <aside class="admin-sidebar">
        <div class="brand"><span class="brand-mark">V</span> Vola</div>
        <nav class="admin-nav">
            <a href="/operateur/dashboard"><i class="ti ti-layout-dashboard"></i> Tableau de bord</a>
            <a href="/users"><i class="ti ti-users"></i> Utilisateurs</a>
            <a href="/operateur" class="ti ti-category"><i class="ti ti-building-bank"></i>Opérateurs</a>
            <a href="/configurations" class="active"><i class="ti ti-settings"></i> Configurations</a>
            <a href="/type-operations"><i class="ti ti-category"></i> Types d'operation</a>
            <a href="/frais-operations"><i class="ti ti-receipt-2"></i> Frais d'operation</a>
            <a href="/operateur/situation-gain"><i class="ti ti-chart-bar"></i> Situation des gains</a>
            <a href="/operateur/situation-gain-client"><i class="ti ti-user-search"></i> Situation client</a>
            <a href="/user/logout" style="margin-top:16px;"><i class="ti ti-logout"></i> Deconnexion</a>
        </nav>
    </aside>

    <main class="admin-main">

        <div class="admin-topline">
            <h1>Configurations</h1>
            <a href="/configurations/create" class="btn btn-secondary btn-sm"><i class="ti ti-plus"></i> Ajouter une
                configuration</a>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert success" style="margin:0 0 16px;"><i
                        class="ti ti-circle-check"></i> <?= esc(session()->getFlashdata('success')) ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert error" style="margin:0 0 16px;"><i
                        class="ti ti-alert-circle"></i> <?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>

        <div class="table-card">
            <table class="data">
                <tr>
                    <th>ID</th>
                    <th>Operateur</th>
                    <th>Prefixe</th>
                    <th>Actions</th>
                </tr>

                <?php foreach ($configurations as $configuration): ?>
                    <tr>
                        <td>#<?= esc($configuration['id']) ?></td>
                        <td>
                            <span class="badge <?= $configuration['is_principale'] ? 'bg-primary' : 'bg-secondary' ?>"><?= esc($configuration['operateur_libelle']) ?></span>
                        </td>
                        <td><?= esc($configuration['prefix']) ?></td>
                        <td>
                            <div class="row-actions">
                                <a href="/configurations/<?= esc($configuration['id']) ?>/edit"
                                   class="btn btn-ghost btn-sm"><i class="ti ti-edit"></i> Modifier</a>
                                <form method="post" action="/configurations/<?= esc($configuration['id']) ?>/delete"
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


