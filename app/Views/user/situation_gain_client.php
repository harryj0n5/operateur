<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vola - Situation d'un client</title>
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/bootstrap/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>

<div class="admin-shell">

    <?= view('partials/sidebar', ['active' => 'situation-gain-client']) ?>

    <main class="admin-main">

        <a href="/operateur/dashboard" class="back-link"><i class="bi bi-arrow-left"></i> Retour au dashboard</a>
        <h1 style="margin-bottom:20px;">Situation d'un client</h1>

        <?php if ($error): ?>
            <div class="alert error" style="margin:0 0 16px;"><i
                        class="bi bi-exclamation-triangle"></i> <?= esc($error) ?>
            </div>
        <?php endif; ?>

        <div class="form-card" style="margin-bottom:24px;">
            <form method="get" action="/operateur/situation-gain-client"
                  style="display:flex;gap:14px;align-items:flex-end;flex-wrap:wrap;">
                <div class="field" style="margin-bottom:0;flex:1;min-width:200px;">
                    <label>Client</label>
                    <select name="client_id">
                        <option value="">-- Choisir un client --</option>
                        <?php foreach ($clients as $client): ?>
                            <option
                                    value="<?= esc($client['id']) ?>"
                                    <?= (string)$clientId === (string)$client['id'] ? 'selected' : '' ?>
                            >
                                <?= esc($client['telephone']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="field" style="margin-bottom:0;flex:1;min-width:160px;">
                    <label>Jusqu'au</label>
                    <input type="date" name="date" value="<?= esc($date) ?>">
                </div>
                <button class="btn btn-secondary" style="width:auto;padding:13px 22px;">
                    <i class="ti ti-search"></i> Afficher
                </button>
            </form>
        </div>

        <?php if ($situation): ?>
            <div class="stat-grid">
                <div class="stat-card">
                    <div class="stat-label"><i class="ti ti-phone"></i> Telephone</div>
                    <div class="stat-value" style="font-size:18px;"><?= esc($situation['telephone']) ?></div>
                    <div class="stat-sub">Situation au <?= esc($situation['date_situation']) ?></div>
                </div>
                <div class="stat-card">
                    <div class="stat-label"><i class="ti ti-wallet"></i> Solde actuel</div>
                    <div class="stat-value"><?= esc(number_format((float)$situation['solde_actuel'], 0, ',', ' ')) ?>
                        Ar
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-label"><i class="ti ti-arrows-exchange"></i> Transactions</div>
                    <div class="stat-value"><?= esc($situation['nombre_transaction']) ?></div>
                </div>
                <div class="stat-card">
                    <div class="stat-label"><i class="ti ti-arrow-down-left"></i> Total credit</div>
                    <div class="stat-value"
                         style="color:var(--credit);"><?= esc(number_format((float)$situation['total_credit'], 0, ',', ' ')) ?>
                        Ar
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-label"><i class="ti ti-arrow-up-right"></i> Total debit</div>
                    <div class="stat-value"
                         style="color:var(--debit);"><?= esc(number_format((float)$situation['total_debit'], 0, ',', ' ')) ?>
                        Ar
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-label"><i class="ti ti-receipt-2"></i> Total frais</div>
                    <div class="stat-value"><?= esc(number_format((float)$situation['total_frais'], 0, ',', ' ')) ?>
                        Ar
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </main>

</div>

</body>
</html>


