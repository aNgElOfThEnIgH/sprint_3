<?php include('valida_sessao.php'); ?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel Principal</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
    <div class="container">
        <h2>Bem-vindo, <?php echo $_SESSION['usuario']; ?></h2>
        <ul>
            <li><a href="cadastro_cliente.php">Cadastro de Clientes</a></li>
            <li><a href="cadastro_animal.php">Cadastro de Animais</a></li>
            <li><a href="cadastro_consulta.php">Cadastro de Consulta</a></li>
            <li><a href="cadastro_vet.php">Cadastro de Veterinário</a></li>
            <li><a href="listagem_animais.php">Listagem de Animais</a></li>
            <li><a href="listagem_consulta.php">Listagem de Consultas</a></li>
            <li><a href="logout.php">Sair</a></li>
        </ul>
    </div>
</body>
</html>
