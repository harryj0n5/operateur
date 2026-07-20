<!doctype html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Vola - Modifier opérateur</title>


    <link rel="stylesheet" href="/assets/css/tabler-icons-fallback.css">
    <link rel="stylesheet" href="/assets/css/app.css">

</head>


<body>


<div class="admin-shell">

    <aside class="admin-sidebar">
        <div class="brand"><span class="brand-mark">V</span> Vola</div>
        <nav class="admin-nav">
            <a href="/operateur/dashboard"><i class="ti ti-layout-dashboard"></i> Tableau de bord</a>
            <a href="/users"><i class="ti ti-users"></i> Utilisateurs</a>
            <a href="/operateur" class="active"><i class="active"></i> Opérateurs</a>
            <a href="/configurations"><i class="ti ti-settings"></i> Configurations</a>
            <a href="/type-operations"><i class="ti ti-category"></i> Types d'operation</a>
            <a href="/frais-operations" class="active"><i class="ti ti-receipt-2"></i> Frais d'operation</a>
            <a href="/operateur/situation-gain"><i class="ti ti-chart-bar"></i> Situation des gains</a>
            <a href="/operateur/situation-gain-client"><i class="ti ti-user-search"></i> Situation client</a>
            <a href="/user/logout" style="margin-top:16px;"><i class="ti ti-logout"></i> Deconnexion</a>
        </nav>
    </aside>

    <main class="admin-main">


        <a href="/operateurs"
           class="back-link">

            <i class="ti ti-arrow-left"></i>

            Retour aux opérateurs

        </a>


        <h1 style="margin-bottom:20px;">
            Modifier un opérateur
        </h1>


        <div class="form-card">


            <form method="post"
                  action="/operateur/<?= esc($operateur['id']) ?>/update">


                <?= csrf_field() ?>


                <div class="field">


                    <label>Libellé</label>


                    <input type="text"
                           name="libelle"
                           value="<?= esc(old('libelle') ?? $operateur['libelle']) ?>">


                </div>


                <div class="field">


                    <label>Opérateur principal</label>


                    <select name="principale">


                        <option value="1"
                            <?= $operateur['principale'] == 1 ? 'selected' : '' ?>>
                            Oui
                        </option>


                        <option value="0"
                            <?= $operateur['principale'] == 0 ? 'selected' : '' ?>>
                            Non
                        </option>


                    </select>


                </div>


                <div class="field">


                    <label>Pourcentage frais</label>


                    <input type="number"
                           step="0.01"
                           name="pourcentage_frais"
                           value="<?= esc(old('pourcentage_frais') ?? $operateur['pourcentage_frais']) ?>">


                </div>


                <div class="actions-row">


                    <button class="btn btn-secondary">

                        <i class="ti ti-check"></i>

                        Modifier

                    </button>


                    <a href="/operateur"
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