<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vola - Frais d'operation</title>
    <link rel="stylesheet" href="/assets/css/tabler-icons-fallback.css">
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>

<div class="admin-shell">

    <aside class="admin-sidebar">
        <div class="brand"><span class="brand-mark">V</span> Vola</div>
        <nav class="admin-nav">
            <a href="/operateur/dashboard"><i class="ti ti-layout-dashboard"></i> Tableau de bord</a>
            <a href="/users"><i class="ti ti-users"></i> Utilisateurs</a>
            <a href="/operateur"><i class="ti ti-building-bank"></i> Opérateurs</a>
            <a href="/configurations"><i class="ti ti-settings"></i> Configurations</a>
            <a href="/type-operations"><i class="ti ti-category"></i> Types d'operation</a>
            <a href="/frais-operations" class="active"><i class="ti ti-receipt-2"></i> Frais d'operation</a>
            <a href="/operateur/situation-gain"><i class="ti ti-chart-bar"></i> Situation des gains</a>
            <a href="/operateur/situation-gain-client"><i class="ti ti-user-search"></i> Situation client</a>
            <a href="/user/logout" style="margin-top:16px;"><i class="ti ti-logout"></i> Deconnexion</a>
        </nav>
    </aside>

    <main class="admin-main">

        <div class="admin-topline">
            <h1>Frais d'operation</h1>
            <a href="/frais-operations/create" class="btn btn-secondary btn-sm"><i class="ti ti-plus"></i> Ajouter un frais</a>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert success" style="margin:0 0 16px;"><i class="ti ti-circle-check"></i> <?= esc(session()->getFlashdata('success')) ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert error" style="margin:0 0 16px;"><i class="ti ti-alert-circle"></i> <?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>

        <div class="table-card">
            <table class="data">
                <tr>
                    <th>ID</th>
                    <th>Type d'operation</th>
                    <th>Montant min</th>
                    <th>Montant max</th>
                    <th>Frais</th>
                    <th>Actions</th>
                </tr>

                <?php foreach ($fraisOperations as $fraisOperation): ?>
                    <tr>
                        <td>#<?= esc($fraisOperation['id']) ?></td>
                        <td><span class="badge op"><?= esc($fraisOperation['type_operation_libelle']) ?></span></td>
                        <td class="money"><?= esc(number_format((float) $fraisOperation['montant_min'], 0, ',', ' ')) ?> Ar</td>
                        <td class="money"><?= esc(number_format((float) $fraisOperation['montant_max'], 0, ',', ' ')) ?> Ar</td>
                        <td class="money"><?= esc(number_format((float) $fraisOperation['frais'], 0, ',', ' ')) ?> Ar</td>
                        <td>
                            <div class="row-actions">
                                <a href="/frais-operations/<?= esc($fraisOperation['id']) ?>/edit" class="btn btn-ghost btn-sm"><i class="ti ti-edit"></i> Modifier</a>
                                <form method="post" action="/frais-operations/<?= esc($fraisOperation['id']) ?>/delete" onsubmit="return confirm('Supprimer ?')">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-danger btn-sm"><i class="ti ti-trash"></i> Supprimer</button>
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


