<?php include('valida_sessao.php'); ?>
<?php include('conexao.php'); ?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $CodFuncionario = $_POST['CodFuncionario'];
    $Nome = $_POST['Nome'];
    $Funcao = $_POST['Funcao'];
    $Telefone = $_POST['Telefone'];
    $Email = $_POST['Email'];

    if ($CodFuncionario) {
        $sql = "UPDATE Funcionarios SET Nome='$Nome', Funcao='$Funcao', Telefone='$Telefone', Email='$Email' WHERE CodFuncionario='$CodFuncionario'";
        $mensagem = "Funcionário atualizado com sucesso!";
    } else {
        $sql = "INSERT INTO Funcionarios (Nome, Funcao, Telefone, Email) VALUES ('$Nome', '$Funcao', '$Telefone', '$Email')";
        $mensagem = "Funcionário cadastrado com sucesso!";
    }

    if ($conn->query($sql) !== TRUE) {
        $mensagem = "Erro: " . $conn->error;
    }
}

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM Funcionarios WHERE CodFuncionario='$delete_id'";
    if ($conn->query($sql) === TRUE) {
        $mensagem = "Funcionário excluído com sucesso!";
    } else {
        $mensagem = "Erro ao excluir Funcionário: " . $conn->error;
    }
}

$Funcionarios = $conn->query("SELECT * FROM Funcionarios");

$Funcionario = null;
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $Funcionario = $conn->query("SELECT * FROM Funcionarios WHERE CodFuncionario='$edit_id'")->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Funcionário</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
    <div class="container">
        <h2>Cadastro de Funcionário</h2>
        <form method="post" action="">
            <input type="hidden" name="CodFuncionario" value="<?php echo $Funcionario['CodFuncionario'] ?? ''; ?>">
            <label for="Nome">Nome:</label>
            <input type="text" name="Nome" value="<?php echo $Funcionario['Nome'] ?? ''; ?>" required>
            <label for="Funcao">Função:</label>
            <select name="Funcao" required>
                <option value="">Selecione</option>
                <option value="piloto" <?php if (($Funcionario['Funcao'] ?? '') == 'piloto') echo 'selected'; ?>>Piloto</option>
                <option value="técnico" <?php if (($Funcionario['Funcao'] ?? '') == 'técnico') echo 'selected'; ?>>Técnico</option>
                <option value="administrativo" <?php if (($Funcionario['Funcao'] ?? '') == 'administrativo') echo 'selected'; ?>>Administrativo</option>
            </select>
            <label for="Telefone">Telefone:</label>
            <input type="text" name="Telefone" value="<?php echo $Funcionario['Telefone'] ?? ''; ?>">
            <label for="Email">Email:</label>
            <input type="email" name="Email" value="<?php echo $Funcionario['Email'] ?? ''; ?>">
            <button type="submit"><?php echo $Funcionario ? 'Atualizar' : 'Cadastrar'; ?></button>
        </form>
        <?php if (isset($mensagem)) echo "<p class='message " . ($conn->error ? "error" : "success") . "'>$mensagem</p>"; ?>

        <h2>Lista de Funcionários</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Função</th>
                <th>Telefone</th>
                <th>Email</th>
                <th>Ações</th>
            </tr>
            <?php while ($row = $Funcionarios->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['CodFuncionario']; ?></td>
                <td><?php echo $row['Nome']; ?></td>
                <td><?php echo ucfirst($row['Funcao']); ?></td>
                <td><?php echo $row['Telefone']; ?></td>
                <td><?php echo $row['Email']; ?></td>
                <td>
                    <a href="?edit_id=<?php echo $row['CodFuncionario']; ?>">Editar</a>
                    <a href="?delete_id=<?php echo $row['CodFuncionario']; ?>" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
        <a href="index.php">Voltar</a>
    </div>
</body>
</html>