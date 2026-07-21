<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vola - Ajouter une configuration</title>
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/bootstrap/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>

<div class="admin-shell">

    <?= view('partials/sidebar', ['active' => 'configurations']) ?>

    <main class="admin-main">

        <a href="<?= site_url('configurations') ?>" class="back-link"><i class="bi bi-arrow-left"></i> Retour aux
            configurations</a>
        <h1 style="margin-bottom:20px;">Ajouter une configuration</h1>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert error" style="margin:0 0 16px;">
                <i class="bi bi-exclamation-circle"></i> <?= esc(session()->getFlashdata('error')) ?>
            </div>
        <?php endif; ?>

        <div class="form-card">

            <form method="post" action="<?= site_url('configurations/store') ?>">

                <?= csrf_field() ?>

                <div class="field">
                    <label>Prefixe</label>
                    <input type="text"
                           name="prefix"
                           value="<?= esc(old('prefix')) ?>"
                           placeholder="Ex: 034">
                </div>

                <div class="field">
                    <label>Opérateur</label>

                    <select name="operateur_id">
                        <option value="">-- Choisir un opérateur --</option>

                        <?php foreach ($operateurs as $operateur): ?>
                            <option
                                    value="<?= esc($operateur['id']) ?>"
                                    <?= old('operateur_id') == $operateur['id'] ? 'selected' : '' ?>
                            >
                                <?= esc($operateur['libelle']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="actions-row">
                    <button class="btn btn-secondary">
                        <i class="bi bi-check-lg"></i> Enregistrer
                    </button>

                    <a href="<?= site_url('configurations') ?>" class="btn btn-outline">Annuler</a>
                </div>

            </form>

        </div>

    </main>

</div>

</body>
</html>