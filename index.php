<?php ('valida_sessao.php'); ?>

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
            <li><a href="cadastro_drone.php">Cadastro de Drone</a></li>
            <li><a href="cadastro_empresa.php">Cadastro de empresa</a></li>
            <li><a href="cadastro_funcionario.php">Cadastro de Funcionário</a></li>
            <li><a href="cadastro_pedido.php">Cadastro de Pedido</a></li>
            <li><a href="listagem_drones.php">Listagem de Drones</a></li>
            <li><a href="listagem_pedidos.php">Listagem de Pedidos</a></li>
            <li><a href="logout.php">Sair</a></li>
        </ul>
    </div>
</body>
</html>
