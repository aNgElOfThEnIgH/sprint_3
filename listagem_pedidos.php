<?php include('valida_sessao.php'); ?>
<?php include('conexao.php'); ?>

<?php
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM Pedidos WHERE CodPedido='$delete_id'";
    if ($conn->query($sql) === TRUE) {
        $mensagem = "Pedido excluído com sucesso!";
    } else {
        $mensagem = "Erro ao excluir Pedido: " . $conn->error;
    }
}

$Pedidos = $conn->query("SELECT p.CodPedido, p.DataPedido, p.StatusPedido, 
    e.NomeEmpresa AS Empresa_nome, d.Modelo AS Drone_modelo
    FROM Pedidos p
    JOIN Empresas e ON p.CodEmpresa = e.CodEmpresa
    JOIN drones d ON p.CodDrone = d.CodDrone
    ORDER BY p.DataPedido DESC");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Listagem de Pedidos</title>
    <link rel="stylesheet" href="css/animal.css">
</head>
<body>
    <div class="container">
        <h2>Listagem de Pedidos</h2>
        <?php if (isset($mensagem)) echo "<p class='message " . ($conn->error ? "error" : "success") . "'>$mensagem</p>"; ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Empresa</th>
                <th>Drone</th>
                <th>Data do Pedido</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
            <?php while ($row = $Pedidos->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['CodPedido']; ?></td>
                <td><?php echo $row['Empresa_nome']; ?></td>
                <td><?php echo $row['Drone_modelo']; ?></td>
                <td><?php echo date('d/m/Y H:i', strtotime($row['DataPedido'])); ?></td>
                <td><?php echo $row['StatusPedido']; ?></td>
                <td>
                    <a href="cadastro_pedido.php?edit_id=<?php echo $row['CodPedido']; ?>">Editar</a>
                    <a href="?delete_id=<?php echo $row['CodPedido']; ?>" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
        <a href="index.php">Voltar</a>
    </div>
</body>
</html>