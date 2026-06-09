<?php
session_start();

if(isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}

$erro = '';

if($_SERVER['REQUEST_METHOD']==='POST') {
    require_once 'db.php';

    $usuario = $_POST['usuario'] ??'';
    $senha = $_POST['senha'] ??'';

    $stmt = $db->prepare('SELECT * FROM usuarios WHERE usuario =:usuario');
    $stmt->execute([':usuario' => $usuario]);
    $user = $stmt->fetch();

    if($user && password_verify($senha, $user['senha'])) {
        $_SESSION['usuario_id'] =$user['id'];
        $_SESSION['usuario_nome'] =$user['ususario'];
        header('Location: index.php');
        exit;
    } else{
        $erro = 'Usuario ou senha incorretos.';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Login</title>
        <link rel="stylesheet" href="styles.css">
    </head>

    <body>
        <h1>Login</h1>

        <?php if ($erro): ?>
            <p class="erro"><?php echo $erro; ?></p>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="usuario" placeholder="Usuário">
            <input type="password" name="senha" placeholder="Senha">
            <button type="submit" class="btn-adicionar">Entrar</button>
        </form>
    </body>
</html>