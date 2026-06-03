<?php
    
    $db = new PDO('sqlite:tarefas.db');
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $db->exec('CREATE TABLE IF NOT EXISTS tarefas(
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        descricao TEXT NOT NULL
    )');

//ADICIONAR TAREfa
    if ($_SERVER['REQUEST_METHOD']=== 'POST' && isset($_POST['tarefa'])) {
        $novaTarefa = $_POST['tarefa'];
        if(!empty($novaTarefa)){
            $stmt = $db->prepare('INSERT INTO tarefas (descricao) VALUES (:descricao)');
            $stmt->execute([':descricao'=> $novaTarefa]);
        }
        echo "<script>window.location='index.php';</script>";
        exit;
    }

//REMOVER TAREFAS
    if($_SERVER['REQUEST_METHOD'] ==='POST' && isset($_POST['deletar'])) {
    $stmt =$db->prepare('DELETE FROM tarefas WHERE id = :id');
    $stmt->execute([':id' => $_POST['deletar']]);  
    echo "<script>window.location='index.php';</script>";
    exit;      
    }

//EDITAR - SALVAR
    if($_SERVER['REQUEST_METHOD'] ==='POST' && isset($_POST['editar_id'])) {
    $stmt =$db->prepare('UPDATE tarefas SET descricao = :descricao WHERE id=:id');
    $stmt->execute([':descricao' => $_POST['editar_descricao'], ':id' => $_POST['editar_id']]);  
    echo "<script>window.location='index.php';</script>";
    exit;      
    }
//EDITAR - ABRIR
    $editarID = $_GET['editar'] ?? null;
    
    $tarefas = $db->query('SELECT * FROM tarefas')->fetchAll();


?>



<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Lista de Tarefas</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <h1>Minhas Tarefas</h1>

  <form method="POST">
    <input type="text" name="tarefa" placeholder="Digite uma tarefa...">
    <button type="submit" class="btn-adicionar" >Adicionar</button>
  </form>

  <ul>
    <?php if (empty($tarefas)):?>
        <p>Nenhuma tarefa ainda</p>
    <?php else: ?>
        <?php foreach ($tarefas as $tarefa): ?>
            <li>
                <?php if ($editarID == $tarefa['id']): ?>
                    <form method="POST">
                        <input type="hidden" name="editar_id" value="<?php echo $tarefa['id']; ?>">
                        <input type="text" name="editar_descricao" value="<?php echo htmlspecialchars($tarefa['descricao']); ?>">
                        <button type="submit">Salvar</button>
                        <a href="index.php">Cancelar</a>
                    </form>
                <?php else: ?>
                    <?php echo htmlspecialchars($tarefa['descricao']); ?>
                    <a href="index.php?editar=<?php echo $tarefa['id']; ?>">✏️</a>
                    <form method="POST" style="display:inline">
                        <input type="hidden" name="deletar" value="<?php echo $tarefa['id']; ?>">
                        <button type="submit"class="btn-deletar">🗑️</button>
                    </form>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    <?php endif; ?>
  </ul>

</body>
</html>