<!doctype html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vola - Opérateurs</title>
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/bootstrap/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/app.css">
</head>

<body>


<div class="admin-shell">


    <?= view('partials/sidebar', ['active' => 'operateur']) ?>

    <main class="admin-main">


        <div class="admin-topline">

            <h1>Opérateurs</h1>


            <a href="/operateur/create"
               class="btn btn-secondary btn-sm">

                <i class="bi bi-plus"></i>
                Ajouter un opérateur

            </a>


        </div>


        <?php if (session()->getFlashdata('success')): ?>

            <div class="alert success">

                <i class="ti ti-circle-check"></i>

                <?= esc(session()->getFlashdata('success')) ?>

            </div>

        <?php endif; ?>



        <?php if (session()->getFlashdata('error')): ?>

            <div class="alert error">

                <i class="ti ti-alert-circle"></i>

                <?= esc(session()->getFlashdata('error')) ?>

            </div>

        <?php endif; ?>


        <div class="table-card">


            <table class="data">


                <tr>

                    <th>ID</th>
                    <th>Libellé</th>
                    <th>Principal</th>
                    <th>Pourcentage frais</th>
                    <th>Actions</th>

                </tr>


                <?php foreach ($operateurs as $operateur): ?>


                    <tr>


                        <td>
                            #<?= esc($operateur['id']) ?>
                        </td>


                        <td>

<span class="badge op">

<?= esc($operateur['libelle']) ?>

</span>

                        </td>


                        <td>

                            <?php if ($operateur['principale']): ?>

                                <span class="badge success">
Oui
</span>

                            <?php else: ?>

                                <span class="badge">
Non
</span>

                            <?php endif; ?>


                        </td>


                        <td class="money">

                            <?= esc($operateur['pourcentage_frais']) ?> %

                        </td>


                        <td>


                            <div class="row-actions">


                                <a href="/operateur/<?= esc($operateur['id']) ?>/edit"
                                   class="btn btn-ghost btn-sm">

                                    <i class="ti ti-edit"></i>

                                    Modifier

                                </a>


                                <form method="post"
                                      action="/operateur/<?= esc($operateur['id']) ?>/delete"
                                      onsubmit="return confirm('Supprimer ?')">


                                    <?= csrf_field() ?>


                                    <button class="btn btn-danger btn-sm">

                                        <i class="ti ti-trash"></i>

                                        Supprimer

                                    </button>


                                </form>


                            </div>


                        </td>


                    </tr>


                <?php endforeach; ?>


            </table>


        </div>


    </main>


</div>


</body>

</html>