<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vola - Modifier un frais d'operation</title>
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/bootstrap/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>

<div class="admin-shell">


    <?= view('partials/sidebar', ['active' => 'frais_operations']) ?>

    <main class="admin-main">

        <a href="<?= site_url('/frais-operations') ?>" class="back-link"><i class="bi bi-arrow-left"></i> Retour aux
            frais d'operation</a>
        <h1 style="margin-bottom:20px;">Modifier un frais d'operation</h1>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert error" style="margin:0 0 16px;"><i
                        class="ti ti-alert-circle"></i> <?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>

        <div class="form-card">
            <form method="post" action="/frais-operations/<?= esc($fraisOperation['id']) ?>/update">
                <?= csrf_field() ?>

                <div class="field">
                    <label>Type d'operation</label>
                    <select name="type_operation_id">
                        <?php foreach ($typeOperations as $typeOperation): ?>
                            <option
                                    value="<?= esc($typeOperation['id']) ?>"
                                    <?= (string)(old('type_operation_id') ?? $fraisOperation['type_operation_id']) === (string)$typeOperation['id'] ? 'selected' : '' ?>
                            >
                                <?= esc($typeOperation['libelle']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="field">
                    <label>Montant min</label>
                    <input type="number" step="any" name="montant_min"
                           value="<?= esc(old('montant_min') ?? $fraisOperation['montant_min']) ?>">
                </div>

                <div class="field">
                    <label>Montant max</label>
                    <input type="number" step="any" name="montant_max"
                           value="<?= esc(old('montant_max') ?? $fraisOperation['montant_max']) ?>">
                </div>

                <div class="field">
                    <label>Frais</label>
                    <input type="number" step="any" name="frais"
                           value="<?= esc(old('frais') ?? $fraisOperation['frais']) ?>">
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


