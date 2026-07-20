<?php
$prefixes = $prefixes ?? [];
$prefixesStr = implode(',', $prefixes);

$placeholder = !empty($prefixes)
        ? 'Ex: ' . $prefixes[0] . 'XXXXXXX'
        : 'Numéro de téléphone';
?>

<!doctype html>
<html lang="fr">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vola - Connexion</title>
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/bootstrap/icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/app.css">


    <style>

        body {
            min-height: 100vh;
            background: #f4f7fb;
        }


        .auth-wrapper {

            min-height: 100vh;

        }


        .hero {

            background: linear-gradient(
                    135deg,
                    #0d6efd,
                    #4c8df8
            );

            color: white;

            position: relative;

            overflow: hidden;

        }


        .hero::before {

            content: "";

            position: absolute;

            width: 350px;
            height: 350px;

            background: rgba(255, 255, 255, .15);

            border-radius: 50%;

            top: -100px;
            left: -100px;

        }


        .hero::after {

            content: "";

            position: absolute;

            width: 300px;
            height: 300px;

            background: rgba(255, 255, 255, .1);

            border-radius: 50%;

            bottom: -120px;
            right: -100px;

        }


        .brand-icon {

            width: 100px;
            height: 100px;

            border-radius: 50%;

            background: rgba(255, 255, 255, .18);

            display: flex;

            align-items: center;
            justify-content: center;

            font-size: 45px;

            margin: auto;

            backdrop-filter: blur(10px);

        }


        .login-card {

            max-width: 430px;

            width: 100%;

            border-radius: 28px !important;

        }


        .form-control {

            height: 55px;

            border-radius: 0 14px 14px 0;

        }


        .input-group-text {

            border-radius: 14px 0 0 14px;

            background: white;

        }


        .btn-login {

            height: 55px;

            border-radius: 14px;

            font-weight: 600;

            transition: .25s;

        }


        .btn-login:hover {

            transform: translateY(-2px);

            box-shadow: 0 10px 25px rgba(13, 110, 253, .3);

        }


        @media (max-width: 768px) {

            .hero {

                display: none;

            }

        }


    </style>


</head>


<body>


<div class="container-fluid">
    <div class="row auth-wrapper">
        <div class="col-lg-6 hero d-flex align-items-center justify-content-center">
            <div class="text-center position-relative">
                <div class="brand-icon mb-4">

                    <i class="bi bi-wallet2"></i>

                </div>


                <h1 class="display-3 fw-bold">

                    Vola

                </h1>


                <p class="lead">

                    Votre argent,
                    partout,
                    en toute confiance.

                </p>


            </div>


        </div>


        <!-- Formulaire -->

        <div class="col-lg-6 d-flex align-items-center justify-content-center">


            <div class="card login-card border-0 shadow-lg">


                <div class="card-body p-5">


                    <div class="text-center mb-4">


                        <h2 class="fw-bold">

                            Connexion

                        </h2>


                        <p class="text-muted">

                            Entrez votre numéro pour accéder à votre compte.

                        </p>


                    </div>


                    <form id="loginForm">


                        <div class="mb-4">


                            <label class="form-label fw-semibold">

                                Numéro de téléphone

                            </label>


                            <div class="input-group">


                            <span class="input-group-text">

                                <i class="bi bi-phone"></i>

                            </span>


                                <input

                                        type="tel"

                                        id="telephone"

                                        name="telephone"

                                        class="form-control"

                                        placeholder="<?= esc($placeholder) ?>"

                                        data-prefixes="<?= esc($prefixesStr) ?>"

                                        required

                                >


                            </div>


                            <div class="form-text mt-2">


                                <i class="bi bi-shield-check text-success"></i>

                                Préfixes acceptés :

                                <?= esc(implode(', ', $prefixes)) ?>


                            </div>


                        </div>


                        <div

                                id="errorMessage"

                                class="alert alert-danger d-none">

                        </div>


                        <button

                                type="submit"

                                class="btn btn-primary btn-login w-100">


                            <i class="bi bi-box-arrow-in-right"></i>

                            Se connecter


                        </button>


                    </form>


                </div>


            </div>


        </div>


    </div>


</div>


<script>


    const telephoneInput =
        document.getElementById('telephone');


    const prefixesAutorises =
        telephoneInput.dataset.prefixes
            .split(',');


    const errorBox =
        document.getElementById('errorMessage');


    function showError(message) {

        errorBox.textContent = message;

        errorBox.classList.toggle(
            'd-none',
            !message
        );

    }


    document
        .getElementById('loginForm')
        .addEventListener(
            'submit',
            async function (event) {


                event.preventDefault();


                const telephone =
                    telephoneInput.value.trim();


                const prefixeValide =
                    prefixesAutorises.some(
                        p => telephone.startsWith(p)
                    );


                if (prefixesAutorises.length && !prefixeValide) {

                    showError(
                        'Le numéro doit commencer par : '
                        +
                        prefixesAutorises.join(', ')
                    );

                    return;

                }


                try {


                    const response =
                        await fetch(
                            '/user/login',
                            {

                                method: 'POST',

                                headers: {

                                    'Content-Type':
                                        'application/json'

                                },

                                body:
                                    JSON.stringify(
                                        {
                                            telephone
                                        }
                                    )

                            }
                        );


                    const data =
                        await response.json();


                    if (response.ok) {


                        window.location.href =
                            Number(data.type_user_id) === 1

                                ? '/operateur/dashboard'

                                : '/client/dashboard';


                    } else {


                        showError(data.error);


                    }


                } catch (error) {


                    showError(
                        'Une erreur est survenue. Veuillez réessayer.'
                    );


                }


            });


</script>


<script src="/assets/bootstrap/js/bootstrap.bundle.min.js"></script>


</body>

</html>