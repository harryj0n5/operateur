<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vola - Situation des gains</title>
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/bootstrap/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>

<div class="admin-shell">

    <?= view('partials/sidebar', ['active' => 'situation-gain']) ?>

    <main class="admin-main">

        <a href="/operateur/dashboard" class="back-link"><i class="bi bi-arrow-left"></i> Retour au dashboard</a>
        <h1 style="margin-bottom:20px;">Situation des gains</h1>

        <?php if ($error): ?>
            <div class="alert error" style="margin:0 0 16px;"><i
                        class="bi bi-exclamation-triangle"></i> <?= esc($error) ?>
            </div>
        <?php endif; ?>

        <div class="form-card" style="margin-bottom:24px;">
            <form method="get" action="/operateur/situation-gain"
                  style="display:flex;gap:14px;align-items:flex-end;flex-wrap:wrap;">
                <div class="field" style="margin-bottom:0;flex:1;min-width:180px;">
                    <label>Jusqu'au</label>
                    <input type="date" name="date" value="<?= esc($date) ?>">
                </div>
                <button class="btn btn-secondary" style="width:auto;padding:13px 22px;">
                    <i class="bi bi-search"></i> Afficher
                </button>
            </form>
        </div>

        <?php if ($situation): ?>

            <div class="stat-grid">


                <div class="stat-card">

                    <div class="stat-label">
                        <i class="bi bi-building-bank"></i>
                        Gain opérateur principal
                    </div>

                    <div class="stat-value">

                        <?= esc(
                                number_format(
                                        (float)$situation['total_operateur_principal'],
                                        0,
                                        ',',
                                        ' '
                                )
                        ) ?> Ar

                    </div>

                </div>


                <div class="stat-card">

                    <div class="stat-label">

                        <i class="bi bi-building-community"></i>

                        Gain autres opérateurs

                    </div>


                    <div class="stat-value">

                        <?= esc(
                                number_format(
                                        (float)$situation['total_autres_operateurs'],
                                        0,
                                        ',',
                                        ' '
                                )
                        ) ?> Ar


                    </div>


                </div>


                <div class="stat-card">

                    <div class="stat-label">

                        <i class="bi bi-arrows-exchange"></i>

                        Transactions

                    </div>


                    <div class="stat-value">

                        <?= esc($situation['nombre_transaction']) ?>

                    </div>


                </div>


            </div>


        <?php endif; ?>

        <?php if ($situation): ?>

            <div class="table-card" style="margin-top:24px;">

                <table class="data">

                    <tr>
                        <th>Type opération</th>
                        <th>Gain opérateur principal</th>
                        <th>Gain autre opérateur</th>
                        <th>Nombre transactions</th>
                    </tr>


                    <?php foreach ($situation['par_operation'] as $ligne): ?>


                        <tr>

                            <td>
                                <?= esc($ligne['type_operation_libelle']) ?>
                            </td>


                            <td class="money">

                                <?= esc(
                                        number_format(
                                                (float)$ligne['gain_operateur_principal'],
                                                0,
                                                ',',
                                                ' '
                                        )
                                ) ?> Ar

                            </td>


                            <td class="money">

                                <?= esc(
                                        number_format(
                                                (float)$ligne['gain_autre_operateur'],
                                                0,
                                                ',',
                                                ' '
                                        )
                                ) ?> Ar

                            </td>


                            <td>

                                <?= esc($ligne['nombre_transaction']) ?>

                            </td>


                        </tr>


                    <?php endforeach; ?>


                </table>

            </div>

        <?php endif; ?>

    </main>

</div>

</body>
</html>


