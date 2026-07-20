<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vola - Modifier un frais d'opération</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700&family=Inter:wght@400;500;600;700&family=IBM+Plex+Mono:wght@500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tabler-icons/2.44.0/iconfont/tabler-icons.min.css">
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>

<div class="admin-shell">

    <aside class="admin-sidebar">
        <div class="brand"><span class="brand-mark">V</span> Vola</div>
        <nav class="admin-nav">
            <a href="/operateur/dashboard"><i class="ti ti-layout-dashboard"></i> Tableau de bord</a>
            <a href="/users"><i class="ti ti-users"></i> Utilisateurs</a>
            <a href="/configurations"><i class="ti ti-settings"></i> Configurations</a>
            <a href="/type-operations"><i class="ti ti-category"></i> Types d'opération</a>
            <a href="/frais-operations" class="active"><i class="ti ti-receipt-2"></i> Frais d'opération</a>
            <a href="/operateur/situation-gain"><i class="ti ti-chart-bar"></i> Situation des gains</a>
            <a href="/operateur/situation-gain-client"><i class="ti ti-user-search"></i> Situation client</a>
            <a href="/user/logout" style="margin-top:16px;"><i class="ti ti-logout"></i> Déconnexion</a>
        </nav>
    </aside>

    <main class="admin-main">

        <a href="/frais-operations" class="back-link"><i class="ti ti-arrow-left"></i> Retour aux frais d'opération</a>
        <h1 style="margin-bottom:20px;">Modifier un frais d'opération</h1>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert error" style="margin:0 0 16px;"><i class="ti ti-alert-circle"></i> <?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>

        <div class="form-card">
            <form method="post" action="/frais-operations/<?= esc($fraisOperation['id']) ?>/update">
                <?= csrf_field() ?>

                <div class="field">
                    <label>Type d'opération</label>
                    <select name="type_operation_id">
                        <?php foreach ($typeOperations as $typeOperation): ?>
                            <option
                                value="<?= esc($typeOperation['id']) ?>"
                                <?= (string) (old('type_operation_id') ?? $fraisOperation['type_operation_id']) === (string) $typeOperation['id'] ? 'selected' : '' ?>
                            >
                                <?= esc($typeOperation['libelle']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="field">
                    <label>Montant min</label>
                    <input type="number" step="any" name="montant_min" value="<?= esc(old('montant_min') ?? $fraisOperation['montant_min']) ?>">
                </div>

                <div class="field">
                    <label>Montant max</label>
                    <input type="number" step="any" name="montant_max" value="<?= esc(old('montant_max') ?? $fraisOperation['montant_max']) ?>">
                </div>

                <div class="field">
                    <label>Frais</label>
                    <input type="number" step="any" name="frais" value="<?= esc(old('frais') ?? $fraisOperation['frais']) ?>">
                </div>

                <div class="actions-row">
                    <button class="btn btn-secondary"><i class="ti ti-check"></i> Modifier</button>
                    <a href="/frais-operations" class="btn btn-outline">Annuler</a>
                </div>
            </form>
        </div>

    </main>

</div>

</body>
</html>
