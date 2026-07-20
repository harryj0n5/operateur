<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vola - Modifier une configuration</title>
    <link rel="stylesheet" href="/assets/css/tabler-icons-fallback.css">
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>

<div class="admin-shell">

    <
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

        <a href="/configurations" class="back-link"><i class="ti ti-arrow-left"></i> Retour aux configurations</a>
        <h1 style="margin-bottom:20px;">Modifier une configuration</h1>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert error" style="margin:0 0 16px;"><i
                        class="ti ti-alert-circle"></i> <?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>

        <div class="form-card">

            <form method="post" action="/configurations/<?= esc($configuration['id']) ?>/update">

                <?= csrf_field() ?>


                <div class="field">

                    <label>Prefixe</label>

                    <input type="text"
                           name="prefix"
                           value="<?= esc(old('prefix') ?? $configuration['prefix']) ?>">

                </div>


                <div class="field">

                    <label>Opérateur</label>


                    <select name="operateur_id">


                        <option value="">
                            -- Choisir un opérateur --
                        </option>


                        <?php foreach ($operateurs as $operateur): ?>

                            <option
                                    value="<?= esc($operateur['id']) ?>"
                                    <?=
                                    (old('operateur_id') ?? $configuration['operateur_id']) == $operateur['id']
                                            ? 'selected'
                                            : ''
                                    ?>
                            >

                                <?= esc($operateur['libelle']) ?>

                            </option>


                        <?php endforeach; ?>


                    </select>


                </div>


                <div class="actions-row">


                    <button class="btn btn-secondary">

                        <i class="ti ti-check"></i>
                        Modifier

                    </button>


                    <a href="/configurations"
                       class="btn btn-outline">

                        Annuler

                    </a>


                </div>


            </form>

        </div>

    </main>

</div>

</body>
</html>


