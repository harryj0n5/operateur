<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vola - Historique</title>
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/bootstrap/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>

<div class="client-shell">

    <?= view('partials/header', ['active' => 'historique']) ?>

    <div class="page-topline">
        <a href="<?= site_url('client/dashboard') ?>" class="back-btn" aria-label="Retour">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h1>Historique des transactions</h1>
    </div>

    <?php if (empty($historique)): ?>

        <div class="empty-state">
            <i class="bi bi-receipt"></i>
            <p style="font-weight:600;color:var(--vola-text);margin-bottom:4px;">Aucune transaction pour le moment</p>
            <p>Vos depots, retraits et transferts apparaitront ici.</p>
        </div>

    <?php else: ?>

        <div class="tx-list">
            <?php foreach ($historique as $ligne): ?>
                <?php $isCredit = $ligne['type_mouvement'] === 'credit'; ?>
                <div class="tx-row">
                    <span class="tx-icon <?= $isCredit ? 'credit' : 'debit' ?>">
                        <i class="bi <?= $isCredit ? 'bi-arrow-down-left' : 'bi-arrow-up-right' ?>"></i>
                    </span>
                    <div class="tx-main">
                        <div class="tx-title"><?= esc($ligne['type_operation_libelle']) ?></div>
                        <div class="tx-sub">
                            <?= esc($ligne['date']) ?>
                            <?php if ($ligne['contrepartie_telephone']): ?>
                                &middot; <?= esc($ligne['contrepartie_telephone']) ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="tx-amounts">
                        <div class="tx-amount <?= $isCredit ? 'credit' : 'debit' ?>">
                            <?= $isCredit ? '+' : '-' ?><?= esc(number_format((float)$ligne['montant'], 0, ',', ' ')) ?>
                            Ar
                        </div>
                        <div class="tx-fee">
                            Frais
                            : <?= esc(number_format((float)$ligne['frais'] + $ligne['frais_operateur2'], 0, ',', ' ')) ?>
                            Ar
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    <?php endif; ?>

</div>

</body>
</html>