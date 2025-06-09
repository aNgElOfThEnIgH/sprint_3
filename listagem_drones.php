<?php include('valida_sessao.php'); ?>
<?php include('conexao.php'); ?>

<?php
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM drones WHERE CodDrone='$delete_id'";
    if ($conn->query($sql) === TRUE) {
        $mensagem = "Drone excluído com sucesso!";
    } else {
        $mensagem = "Erro ao excluir Drone: " . $conn->error;
    }
}

$drones = $conn->query("SELECT d.CodDrone, d.Modelo, d.Autonomia, d.Disponibilidade, 
    e.NomeEmpresa AS Empresa_nome, f.Nome AS Funcionario_nome
    FROM drones d
    JOIN Empresas e ON d.CodEmpresa = e.CodEmpresa
    JOIN Funcionarios f ON d.CodFuncionarioResponsavel = f.CodFuncionario");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Listagem de Drones</title>
    <link rel="stylesheet" href="css/animal.css">
</head>
<body>
    <div class="container">
        <h2>Listagem de Drones</h2>
        <?php if (isset($mensagem)) echo "<p class='message " . ($conn->error ? "error" : "success") . "'>$mensagem</p>"; ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Modelo</th>
                <th>Autonomia</th>
                <th>Disponibilidade</th>
                <th>Empresa</th>
                <th>Funcionário Responsável</th>
                <th>Ações</th>
            </tr>
            <?php while ($row = $drones->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['CodDrone']; ?></td>
                <td><?php echo $row['Modelo']; ?></td>
                <td><?php echo $row['Autonomia']; ?></td>
                <td><?php echo ucfirst($row['Disponibilidade']); ?></td>
                <td><?php echo $row['Empresa_nome']; ?></td>
                <td><?php echo $row['Funcionario_nome']; ?></td>
                <td>
                    <a href="cadastro_drone.php?edit_id=<?php echo $row['CodDrone']; ?>">Editar</a>
                    <a href="?delete_id=<?php echo $row['CodDrone']; ?>" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
        <a href="index.php">Voltar</a>
    </div>
</body>
</html>