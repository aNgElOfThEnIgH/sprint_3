<?php include('valida_sessao.php'); ?>
<?php include('conexao.php'); ?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $CodEmpresa = $_POST['CodEmpresa'];
    $NomeEmpresa = $_POST['NomeEmpresa'];
    $Telefone = $_POST['Telefone'];
    $Email = $_POST['Email'];

    if ($CodEmpresa) {
        $sql = "UPDATE Empresas SET NomeEmpresa='$NomeEmpresa', Telefone='$Telefone', Email='$Email' WHERE CodEmpresa ='$CodEmpresa'";
        $mensagem =  "Empresa atualizada com sucesso!";
    } else {
        $sql = "INSERT INTO Empresas (NomeEmpresa, Telefone, Email) VALUES ('$NomeEmpresa', '$Telefone', '$Email')";
        $mensagem = "Empresa cadastrada com sucesso!";
    }

    if ($conn->query($sql) !== TRUE) {
        $mensagem = "Erro: " . $conn->error;
    }
}

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM Empresas WHERE CodEmpresa='$delete_id'";
    if ($conn->query($sql) === TRUE) {
        $mensagem = "Empresa excluída com sucesso!";
    } else {
        $mensagem = "Erro ao excluir Empresa: " . $conn->error;
    }
}

$Empresas = $conn->query("SELECT * FROM Empresas");

$Empresa = null;
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $Empresa = $conn->query("SELECT * FROM Empresas WHERE CodEmpresa='$edit_id'")->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Empresa</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
    <div class="container">
        <h2>Cadastro de Empresa</h2>
        <form method="post" action="">
            <input type="hidden" name="CodEmpresa" value="<?php echo $Empresa['CodEmpresa'] ?? ''; ?>">
            <label for="NomeEmpresa">Nome da Empresa:</label>
            <input type="text" name="NomeEmpresa" value="<?php echo $Empresa['NomeEmpresa'] ?? ''; ?>" required>
            <label for="Telefone">Telefone:</label>
            <input type="text" name="Telefone" value="<?php echo $Empresa['Telefone'] ?? ''; ?>">
            <label for="Email">Email:</label>
            <input type="email" name="Email" value="<?php echo $Empresa['Email'] ?? ''; ?>">
            <button type="submit"><?php echo $Empresa ? 'Atualizar' : 'Cadastrar'; ?></button>
        </form>
        <?php if (isset($mensagem)) echo "<p class='message " . ($conn->error ? "error" : "success") . "'>$mensagem</p>"; ?>

        <h2>Lista de Empresas</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Telefone</th>
                <th>Email</th>
                <th>Ações</th>
            </tr>
            <?php while ($row = $Empresas->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['CodEmpresa']; ?></td>
                <td><?php echo $row['NomeEmpresa']; ?></td>
                <td><?php echo $row['Telefone']; ?></td>
                <td><?php echo $row['Email']; ?></td>
                <td>
                    <a href="?edit_id=<?php echo $row['CodEmpresa']; ?>">Editar</a>
                    <a href="?delete_id=<?php echo $row['CodEmpresa']; ?>" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
        <a href="index.php">Voltar</a>
    </div>
</body>
</html>