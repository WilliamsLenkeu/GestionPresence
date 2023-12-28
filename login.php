<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tableau de Bord - Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/style.css" type="text/css" />
    <link rel="icon" href="./image/logo.jpg" type="image/x-icon">

    <style>
        body {
            background-image: url('./image/background.jpeg');
            background-repeat: no-repeat;
            background-size: cover;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .card {
            background-color: rgba(255, 255, 255, 0.8);
            border: none;
            backdrop-filter: blur(10px);
        }

        .card-header {
            background-color: rgb(68, 68, 68);
            color: #FFF;
            border-radius: 20px;
            border-bottom: none;
        }

        .logo-form {
            max-width: 200px;
            margin-bottom: 15px;
            /* border: 2px solid red; */
        }

        .btn-group button {
            background-color: #444;
            color: #FFF;
            border: none;
        }

        .btn-group .active {
            background-color: #FFF;
            color: #444;
        }

        .form-label,
        .form-control {
            color: #444;
        }

        .btn-primary {
            background-color: #444;
            border: none;
        }

        .btn-primary:hover {
            background-color: #666;
        }
    </style>
</head>

<body>

    <div class="container-fluid vh-100 d-flex justify-content-center align-items-center">
        <div class="card p-4">
            <div class="card-header text-center">
                <img src="./image/logo-removebg-preview.png" alt="Logo de l'application" class="img-fluid mb-3 logo-form"><br>
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-primary active" id="loginBtn">Connexion</button>
                    <button type="button" class="btn btn-primary" id="registerBtn">Inscription</button>
                </div>
            </div>
            <div class="card-body">

                <!-- Formulaire de Connexion -->
                <form action="process_login.php" method="post" id="loginForm">
                    <div class="mb-3">
                        <label for="username" class="form-label">Nom d'utilisateur :</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe :</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Se Connecter</button>
                    </div>
                </form>

                <!-- Formulaire d'Inscription -->
                <form action="process_register.php" method="post" id="registerForm" style="display: none;">
                    <div class="mb-3">
                        <label for="newUsername" class="form-label">Nouveau Nom d'utilisateur :</label>
                        <input type="text" class="form-control" id="newUsername" name="newUsername" required>
                    </div>
                    <div class="mb-3">
                        <label for="newPassword" class="form-label">Nouveau Mot de passe :</label>
                        <input type="password" class="form-control" id="newPassword" name="newPassword" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">S'Inscrire</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Bootstrap Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <script>
        document.getElementById('registerBtn').addEventListener('click', function() {
            document.getElementById('loginForm').style.display = 'none';
            document.getElementById('registerForm').style.display = 'block';
            document.getElementById('loginBtn').classList.remove('active');
            document.getElementById('registerBtn').classList.add('active');
        });

        document.getElementById('loginBtn').addEventListener('click', function() {
            document.getElementById('loginForm').style.display = 'block';
            document.getElementById('registerForm').style.display = 'none';
            document.getElementById('loginBtn').classList.add('active');
            document.getElementById('registerBtn').classList.remove('active');
        });
    </script>
</body>

</html>
