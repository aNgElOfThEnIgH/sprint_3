<?php include('valida_sessao.php');?>
<?php include('conexao.php');?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $CodPedido = $_POST['CodPedido'] ?? null;
    $CodDrone = $_POST['CodDrone'];
    $CodEmpresa = $_POST['CodEmpresa'];
    $DataPedido = $_POST['DataPedido'];
    $StatusPedido = $_POST['StatusPedido'];

    if ($CodPedido) {
        // UPDATE
        $sql = "UPDATE Pedidos SET 
            CodDrone='$CodDrone', 
            CodEmpresa='$CodEmpresa', 
            DataPedido='$DataPedido', 
            StatusPedido='$StatusPedido'
            WHERE CodPedido='$CodPedido'";
        $mensagem = "Pedido atualizado com sucesso!";
    } else {
        // INSERT
        $sql = "INSERT INTO Pedidos (
            CodDrone, CodEmpresa, DataPedido, StatusPedido
        ) VALUES (
            '$CodDrone', '$CodEmpresa', '$DataPedido', '$StatusPedido'
        )";
        $mensagem = "Pedido cadastrado com sucesso!";
    }

    if ($conn->query($sql) !== TRUE) {
        $mensagem = "Erro: " . $conn->error;
    }
}

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM Pedidos WHERE CodPedido='$delete_id'";
    if ($conn->query($sql) === TRUE) {
        $mensagem = "Pedido excluído com sucesso!";
    } else {
        $mensagem = "Erro ao excluir Pedido: " . $conn->error;
    }
}

// Consulta para exibir lista de pedidos
$Pedidos = $conn->query("SELECT p.CodPedido, d.CodDrone, d.Modelo AS Drone_modelo, e.CodEmpresa, e.NomeEmpresa, p.DataPedido, p.StatusPedido
FROM Pedidos p
JOIN drones d ON p.CodDrone = d.CodDrone
JOIN Empresas e ON p.CodEmpresa = e.CodEmpresa
ORDER BY p.DataPedido DESC");

// Consulta para edição
$Pedido = null;
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $Pedido = $conn->query("SELECT * FROM Pedidos WHERE CodPedido='$edit_id'")->fetch_assoc();
}

// Listas para selects
$Drones = $conn->query("SELECT CodDrone, Modelo FROM drones");
$Empresas = $conn->query("SELECT CodEmpresa, NomeEmpresa FROM Empresas");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Pedido - NexDrone</title>
    <link rel="stylesheet" href="css/animal.css">
</head>
<body>
    <div class="container">
        <h2>Cadastro de Pedido</h2>
        <form method="post" action="">
            <input type="hidden" name="CodPedido" value="<?php echo $Pedido['CodPedido'] ?? ''; ?>">
            
            <label for="CodDrone">Drone:</label>
            <select name="CodDrone" required>
                <option value="">Selecione</option>
                <?php while ($row = $Drones->fetch_assoc()): ?>
                    <option value="<?php echo $row['CodDrone']; ?>" <?php if ($Pedido && $Pedido['CodDrone'] == $row['CodDrone']) echo 'selected'; ?>>
                        <?php echo $row['Modelo']; ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label for="CodEmpresa">Empresa:</label>
            <select name="CodEmpresa" required>
                <option value="">Selecione</option>
                <?php while ($row = $Empresas->fetch_assoc()): ?>
                    <option value="<?php echo $row['CodEmpresa']; ?>" <?php if ($Pedido && $Pedido['CodEmpresa'] == $row['CodEmpresa']) echo 'selected'; ?>>
                        <?php echo $row['NomeEmpresa']; ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label for="DataPedido">Data e Hora do Pedido:</label>
            <input type="datetime-local" name="DataPedido" value="<?php echo isset($Pedido['DataPedido']) ? date('Y-m-d\TH:i', strtotime($Pedido['DataPedido'])) : ''; ?>" required>

            <label for="StatusPedido">Status:</label>
            <select name="StatusPedido" required>
                <option value="pendente" <?php if (($Pedido['StatusPedido'] ?? '') == 'pendente') echo 'selected'; ?>>Pendente</option>
                <option value="em_andamento" <?php if (($Pedido['StatusPedido'] ?? '') == 'em_andamento') echo 'selected'; ?>>Em Andamento</option>
                <option value="concluido" <?php if (($Pedido['StatusPedido'] ?? '') == 'concluido') echo 'selected'; ?>>Concluído</option>
                <option value="cancelado" <?php if (($Pedido['StatusPedido'] ?? '') == 'cancelado') echo 'selected'; ?>>Cancelado</option>
            </select>

            <button type="submit"><?php echo $Pedido ? 'Atualizar' : 'Cadastrar'; ?></button>
        </form>

        <?php if (isset($mensagem)) echo "<p class='message " . ($conn->error ? "error" : "success") . "'>$mensagem</p>"; ?>

        <h2>Listagem de Pedidos</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Drone</th>
                <th>Empresa</th>
                <th>Data e Hora</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
            <?php while ($row = $Pedidos->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['CodPedido']; ?></td>
                <td><?php echo $row['Drone_modelo']; ?></td>
                <td><?php echo $row['NomeEmpresa']; ?></td>
                <td><?php echo date('d/m/Y H:i', strtotime($row['DataPedido'])); ?></td>
                <td><?php echo ucfirst(str_replace('_',' ',$row['StatusPedido'])); ?></td>
                <td>
                    <a href="?edit_id=<?php echo $row['CodPedido']; ?>">Editar</a> | 
                    <a href="?delete_id=<?php echo $row['CodPedido']; ?>" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>

        <a href="index.php">Voltar</a>
    </div>
</body>
</html>