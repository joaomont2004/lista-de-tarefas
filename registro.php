<?php
session_start();

if(isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}

$erro = '';
$sucesso ='';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'db.php';

    $usuario = $_POST['usuario'] ?? '';
    $senha = $_POST['senha'] ?? '';

    if (empty($usuario) || empty($senha)) {
        $erro ='Preencha todos os campos.';
    } else {
        $hash = password_hash($senha, PASSWORD_DEFAULT);
        try{
            $stmt = $db->prepare('INSERT INTO usuarios (usuario, senha) VALUES (:usuario, :senha)');
            $stmt->execute([':usuario' => $usuario, ':senha' => $hash]);
            $sucesso = 'Usuario criado! <a href="login.php">Fazer login</a>';
        } catch (Exception $e){
        $erro = 'Esse usuario ja existe.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <tittle>Registro</
        <link rel="stylesheet" href="style.css"> 
    </head>

    <body>
        <h1>Criar conta</h1>

        <?php if($erro): ?>
            <p class="erro"><?php echo $erro; ?></p>
        <?php endif; ?>
        
        <?php if($sucesso): ?>
            <p class="sucesso"><?php echo $sucesso; ?></p>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="usuario" placeholder="Usuário">
            <input type="password" name="senha" placeholder="Senha">
            <button type="submit" class="btn-adicionar">Criar conta</button>
        </form>

        <p><a href="login.php">Já tenho conta</a></p>
    </body>
</html>