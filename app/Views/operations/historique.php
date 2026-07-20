<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vola - Historique</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700&family=Inter:wght@400;500;600;700&family=IBM+Plex+Mono:wght@500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tabler-icons/2.44.0/iconfont/tabler-icons.min.css">
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>

<div class="app" style="padding-bottom:24px;">

    <div class="topbar">
        <a href="/client/dashboard" class="back-btn" aria-label="Retour"><i class="ti ti-arrow-left"></i></a>
        <h1>Historique des transactions</h1>
    </div>

    <div class="form-page" style="padding-top:16px;">

        <?php if (empty($historique)): ?>

            <div class="empty-state">
                <i class="ti ti-receipt-off"></i>
                <p style="font-weight:600;color:var(--ink-700);margin-bottom:4px;">Aucune transaction pour le moment</p>
                <p style="font-size:13px;">Vos dépôts, retraits et transferts apparaîtront ici.</p>
            </div>

        <?php else: ?>

            <div class="list">
                <?php foreach ($historique as $ligne): ?>
                    <?php $isCredit = $ligne['type_mouvement'] === 'credit'; ?>
                    <div class="tx-row">
                        <span class="tx-icon <?= $isCredit ? 'credit' : 'debit' ?>">
                            <i class="ti <?= $isCredit ? 'ti-arrow-down-left' : 'ti-arrow-up-right' ?>"></i>
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
                                <?= $isCredit ? '+' : '-' ?><?= esc(number_format((float) $ligne['montant'], 0, ',', ' ')) ?> Ar
                            </div>
                            <div class="tx-fee">Frais : <?= esc(number_format((float) $ligne['frais'], 0, ',', ' ')) ?> Ar</div>
                            <div class="tx-balance">Solde : <?= esc(number_format((float) $ligne['solde_apres'], 0, ',', ' ')) ?> Ar</div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        <?php endif; ?>

    </div>

</div>

</body>
</html>
