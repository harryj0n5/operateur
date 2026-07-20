<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vola - Ajouter une configuration</title>
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
            <a href="/configurations" class="active"><i class="ti ti-settings"></i> Configurations</a>
            <a href="/type-operations"><i class="ti ti-category"></i> Types d'operation</a>
            <a href="/frais-operations"><i class="ti ti-receipt-2"></i> Frais d'operation</a>
            <a href="/operateur/situation-gain"><i class="ti ti-chart-bar"></i> Situation des gains</a>
            <a href="/operateur/situation-gain-client"><i class="ti ti-user-search"></i> Situation client</a>
            <a href="/user/logout" style="margin-top:16px;"><i class="ti ti-logout"></i> Deconnexion</a>
        </nav>
    </aside>

    <main class="admin-main">

        <a href="/configurations" class="back-link"><i class="ti ti-arrow-left"></i> Retour aux configurations</a>
        <h1 style="margin-bottom:20px;">Ajouter une configuration</h1>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert error" style="margin:0 0 16px;"><i class="ti ti-alert-circle"></i> <?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>

        <div class="form-card">
            <form method="post" action="/configurations/store">
                <?= csrf_field() ?>

                <div class="field">
                    <label>Prefixe</label>
                    <input type="text" name="prefix" value="<?= esc(old('prefix')) ?>" placeholder="Ex: 034">
                </div>

                <div class="actions-row">
                    <button class="btn btn-secondary"><i class="ti ti-check"></i> Enregistrer</button>
                    <a href="/configurations" class="btn btn-outline">Annuler</a>
                </div>
            </form>
        </div>

    </main>

</div>

</body>
</html>


