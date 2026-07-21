<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vola - Ajouter un type d'operation</title>
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/bootstrap/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>

<div class="admin-shell">

    <?= view('partials/sidebar', ['active' => 'type-operations']) ?>

    <main class="admin-main">

        <a href="/type-operations" class="back-link"><i class="bi bi-arrow-left"></i> Retour aux types d'operation</a>
        <h1 style="margin-bottom:20px;">Ajouter un type d'operation</h1>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert error" style="margin:0 0 16px;"><i
                        class="ti ti-alert-circle"></i> <?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>

        <div class="form-card">
            <form method="post" action="/type-operations/store">
                <?= csrf_field() ?>

                <div class="field">
                    <label>Libelle</label>
                    <input type="text" name="libelle" value="<?= esc(old('libelle')) ?>" placeholder="Ex: Depot">
                </div>

                <div class="actions-row">
                    <button class="btn btn-secondary"><i class="ti ti-check"></i> Enregistrer</button>
                    <a href="/type-operations" class="btn btn-outline">Annuler</a>
                </div>
            </form>
        </div>

    </main>

</div>

</body>
</html>


