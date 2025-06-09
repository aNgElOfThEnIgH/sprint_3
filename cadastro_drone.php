<?php include('valida_sessao.php'); ?>
<?php include('conexao.php'); ?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $CodDrone = $_POST['CodDrone'];
    $CodEmpresa = $_POST['CodEmpresa'];
    $CodFuncionarioResponsavel = $_POST['CodFuncionarioResponsavel'];
    $Modelo = $_POST['Modelo'];
    $Autonomia = $_POST['Autonomia'];
    $Disponibilidade = $_POST['Disponibilidade'];

    if ($CodDrone) {
        $sql = "UPDATE drones SET CodEmpresa='$CodEmpresa', Modelo='$Modelo', Autonomia='$Autonomia', Disponibilidade='$Disponibilidade', CodFuncionarioResponsavel='$CodFuncionarioResponsavel' WHERE CodDrone='$CodDrone'";
        $mensagem = "Drone atualizado com sucesso!";
    } else {
        $sql = "INSERT INTO drones (CodEmpresa, Modelo, Autonomia, Disponibilidade, CodFuncionarioResponsavel) VALUES ('$CodEmpresa', '$Modelo', '$Autonomia', '$Disponibilidade', '$CodFuncionarioResponsavel')";
        $mensagem = "Drone cadastrado com sucesso!";
    }

    if ($conn->query($sql) !== TRUE) {
        $mensagem = "Erro: " . $conn->error;
    }
}

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM drones WHERE CodDrone='$delete_id'";
    if ($conn->query($sql) === TRUE) {
        $mensagem = "Drone excluído com sucesso!";
    } else {
        $mensagem = "Erro ao excluir Drone: " . $conn->error;
    }
}

// Corrigindo o SELECT para trazer os nomes corretos das tabelas e campos
$drones = $conn->query("SELECT d.CodDrone, d.Modelo, d.Autonomia, d.Disponibilidade, f.Nome AS Funcionario_nome, e.NomeEmpresa AS Empresa_nome 
FROM drones d 
JOIN Empresas e ON d.CodEmpresa = e.CodEmpresa 
JOIN Funcionarios f ON d.CodFuncionarioResponsavel = f.CodFuncionario");

$Drone = null;
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $Drone = $conn->query("SELECT * FROM drones WHERE CodDrone='$edit_id'")->fetch_assoc();
}

$Empresas = $conn->query("SELECT CodEmpresa, NomeEmpresa FROM Empresas");
$Funcionarios = $conn->query("SELECT CodFuncionario, Nome FROM Funcionarios");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Drone</title>
    <link rel="stylesheet" href="css/animal.css">
</head>
<body>
    <div class="container">
        <h2>Cadastro de Drone</h2>
        <form method="post" action="">
            <input type="hidden" name="CodDrone" value="<?php echo $Drone['CodDrone'] ?? ''; ?>">
            <label for="CodEmpresa">Empresa Contratante:</label>
            <select name="CodEmpresa" required>
                <option value="">Selecione</option>
                <?php 
                // Resetando o ponteiro do resultado
                mysqli_data_seek($Empresas, 0);
                while ($row = $Empresas->fetch_assoc()): ?>
                    <option value="<?php echo $row['CodEmpresa']; ?>" <?php if ($Drone && $Drone['CodEmpresa'] == $row['CodEmpresa']) echo 'selected'; ?>>
                        <?php echo $row['NomeEmpresa']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <label for="CodFuncionarioResponsavel">Funcionário Responsável:</label>
            <select name="CodFuncionarioResponsavel" required>
                <option value="">Selecione</option>
                <?php 
                // Resetando o ponteiro do resultado
                mysqli_data_seek($Funcionarios, 0);
                while ($row = $Funcionarios->fetch_assoc()): ?>
                    <option value="<?php echo $row['CodFuncionario']; ?>" <?php if ($Drone && $Drone['CodFuncionarioResponsavel'] == $row['CodFuncionario']) echo 'selected'; ?>>
                        <?php echo $row['Nome']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <label for="Modelo">Modelo:</label>
            <input type="text" name="Modelo" value="<?php echo $Drone['Modelo'] ?? ''; ?>" required>
            <label for="Autonomia">Autonomia:</label>
            <input type="number" step="0.01" name="Autonomia" value="<?php echo $Drone['Autonomia'] ?? ''; ?>" required>
            <label for="Disponibilidade">Disponibilidade:</label>
            <select name="Disponibilidade" required>
                <option value="disponível" <?php if (($Drone['Disponibilidade'] ?? '') == 'disponível') echo 'selected'; ?>>Disponível</option>
                <option value="indisponível" <?php if (($Drone['Disponibilidade'] ?? '') == 'indisponível') echo 'selected'; ?>>Indisponível</option>
            </select>
            <button type="submit"><?php echo $Drone ? 'Atualizar' : 'Cadastrar'; ?></button>
        </form>
        <?php if (isset($mensagem)) echo "<p class='message " . ($conn->error ? "error" : "success") . "'>$mensagem</p>"; ?>

        <h2>Listagem de Drones</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Modelo</th>
                <th>Autonomia</th>
                <th>Disponibilidade</th>
                <th>Funcionário Responsável</th>
                <th>Empresa</th>
                <th>Ações</th>
            </tr>
            <?php while ($row = $drones->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['CodDrone']; ?></td>
                <td><?php echo $row['Modelo']; ?></td>
                <td><?php echo $row['Autonomia']; ?></td>
                <td><?php echo $row['Disponibilidade']; ?></td>
                <td><?php echo $row['Funcionario_nome']; ?></td>
                <td><?php echo $row['Empresa_nome']; ?></td>
                <td>
                    <a href="?edit_id=<?php echo $row['CodDrone']; ?>">Editar</a>
                    <a href="?delete_id=<?php echo $row['CodDrone']; ?>" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
        <a href="index.php">Voltar</a>
    </div>
</body>
</html>