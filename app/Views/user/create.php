<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vola - Ajouter un utilisateur</title>
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/bootstrap/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>

<div class="admin-shell">

    <?= view('partials/sidebar', ['active' => 'users']) ?>

    <main class="admin-main">

        <a href="<?= site_url('users') ?>" class="back-link"><i class="bi bi-arrow-left"></i> Retour aux utilisateurs</a>
        <h1 style="margin-bottom:20px;">Ajouter un utilisateur</h1>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert error" style="margin:0 0 16px;">
                <i class="bi bi-exclamation-circle"></i> <?= esc(session()->getFlashdata('error')) ?>
            </div>
        <?php endif; ?>

        <div class="form-card">
            <form method="post" action="<?= site_url('users/store') ?>">
                <?= csrf_field() ?>

                <div class="field">
                    <label>Telephone</label>
                    <input type="text" name="telephone" value="<?= esc(old('telephone')) ?>" placeholder="034XXXXXXX">
                </div>

                <div class="field">
                    <label>Solde</label>
                    <input type="number" name="solde" value="<?= esc(old('solde') ?? '0') ?>">
                </div>

                <div class="field">
                    <label>Type utilisateur</label>
                    <select name="type_user_id">
                        <?php foreach ($typeUsers as $typeUser): ?>
                            <option
                                    value="<?= esc($typeUser['id']) ?>"
                                    <?= (string)old('type_user_id') === (string)$typeUser['id'] ? 'selected' : '' ?>
                            >
                                <?= esc($typeUser['libelle']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="actions-row">
                    <button class="btn btn-secondary"><i class="bi bi-check-lg"></i> Enregistrer</button>
                    <a href="<?= site_url('users') ?>" class="btn btn-outline">Annuler</a>
                </div>
            </form>
        </div>

    </main>

</div>

</body>
</html>