<?php
session_start(); // Inicia la sessió per a l'historial d'operacions

// Comprova si el formulari ha estat enviat
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Variables per als inputs
    $num1 = isset($_POST['num1']) ? $_POST['num1'] : null;
    $num2 = isset($_POST['num2']) ? $_POST['num2'] : null;
    $string1 = isset($_POST['string1']) ? $_POST['string1'] : '';
    $string2 = isset($_POST['string2']) ? $_POST['string2'] : '';
    $operador = isset($_POST['operador']) ? $_POST['operador'] : '';
    $resultat = '';

    // Operacions numèriques
    if ($operador == 'factorial') {
        if (!empty($num1) && empty($num2)) {
            $resultat = factorial($num1);
        } else {
            $resultat = 'Error: Només es permet introduir un número per al factorial.';
        }
    } elseif (!empty($num1) && !empty($num2)) {
        switch ($operador) {
            case '+':
                $resultat = $num1 + $num2;
                break;
            case '-':
                $resultat = $num1 - $num2;
                break;
            case '*':
                $resultat = $num1 * $num2;
                break;
            case '/':
                if ($num2 != 0) {
                    $resultat = $num1 / $num2;
                } else {
                    $resultat = 'Error: Divisió per zero!';
                }
                break;
            default:
                $resultat = 'Error: Operador no vàlid!';
                break;
        }
    }

    // Operacions amb strings
    if (!empty($string1)) {
        switch ($operador) {
            case 'concat':
                $resultat = $string1 . $string2;
                break;
            case 'remove':
                $resultat = str_replace($string2, '', $string1);
                break;
            default:
                $resultat = 'Error: Operador no vàlid per a strings!';
                break;
        }
    }

    // Guarda l'operació a l'historial de la sessió
    if (!isset($_SESSION['historial'])) {
        $_SESSION['historial'] = [];
    }
    $_SESSION['historial'][] = "Operació: $operador, Resultat: $resultat";

    echo "<h2>Resultat: $resultat</h2>";
}

// Funció per calcular el factorial d'un nombre
function factorial($n) {
    if (!is_numeric($n) || $n < 0 || $n != floor($n)) {
        return 'Error: El factorial no està definit per a nombres decimals o negatius.';
    } elseif ($n == 0) {
        return 1;
    } else {
        return $n * factorial($n - 1);
    }
}

if (isset($_POST['logout'])) {
    session_unset(); // Esborra totes les variables de sessió
    session_destroy(); // Destrueix la sessió
    echo "<h2>L'historial s'ha esborrat.</h2>";
}
?>

<!-- Afegeix Bootstrap -->
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<div class="container mt-5">
    <div class="row">
        <!-- Operacions numèriques -->
        <div class="col-12 col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title text-center">Operacions numèriques</h3>
                    <form action="" method="post">
                        <div class="mb-3">
                            <label for="num1" class="form-label"></label>
                            <input type="number" class="form-control form-control-sm" id="num1" name="num1" step="any">
                        </div>
                        <div class="mb-3">
                            <label for="num2" class="form-label"></label>
                            <input type="number" class="form-control form-control-sm" id="num2" name="num2" step="any">
                        </div>
                        <div class="mb-3">
                            <label for="operador" class="form-label">Operador:</label><br>
                            <div class="btn-group d-md-block" role="group">
                                <button type="submit" name="operador" value="+" class="btn btn-primary">+</button>
                                <button type="submit" name="operador" value="-" class="btn btn-primary">-</button>
                                <button type="submit" name="operador" value="*" class="btn btn-primary">*</button>
                                <button type="submit" name="operador" value="/" class="btn btn-primary">/</button>
                                <button type="submit" name="operador" value="factorial" class="btn btn-warning">Factorial</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Operacions amb strings -->
        <div class="col-12 col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title text-center">Operacions amb strings</h3>
                    <form action="" method="post">
                        <div class="mb-3">
                            <label for="string1" class="form-label"></label>
                            <input type="text" class="form-control form-control-sm" id="string1" name="string1" required>
                        </div>
                        <div class="mb-3">
                            <label for="string2" class="form-label"></label>
                            <input type="text" class="form-control form-control-sm" id="string2" name="string2" required>
                        </div>
                        <div class="mb-3">
                            <label for="operador" class="form-label">Operador:</label><br>
                            <div class="btn-group d-md-block" role="group">
                                <button type="submit" name="operador" value="concat" class="btn btn-primary">Concatenar</button>
                                <button type="submit" name="operador" value="remove" class="btn btn-danger">Eliminar Substring</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Botó per esborrar historial -->
    <div class="row">
        <div class="col-12 text-center">
            <form action="" method="post">
                <button type="submit" name="logout" class="btn btn-danger">Borrar Historial</button>
            </form>
        </div>
    </div>

    <!-- Historial d'operacions -->
    <div class="row mt-4">
        <div class="col-12">
            <h3 class="text-center text-md-left">Historial d'operacions:</h3>
            <?php
            if (isset($_SESSION['historial'])) {
                echo "<ul class='list-group list-group-flush'>";
                foreach ($_SESSION['historial'] as $operacio) {
                    echo "<li class='list-group-item'>$operacio</li>";
                }
                echo "</ul>";
            }
            ?>
        </div>
    </div>
</div>
