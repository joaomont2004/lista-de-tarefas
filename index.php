<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
  header('Location: login.php');
  exit;
}

require_once 'acoes.php';

$editarID = $_GET['editar'] ?? null;
$filtro = $_GET['filtro'] ?? 'todas';
$where = match($filtro) {
    'pendentes'  => 'AND concluida = 0',
    'concluidas' => 'AND concluida = 1',
    default      => ''
};
$usuarioId = $_SESSION['usuario_id'];

$tarefas = $db->prepare("SELECT * FROM tarefas WHERE usuario_id = :usuario_id $where ORDER BY created_at ASC");
$tarefas->execute([':usuario_id' => $usuarioId]);
$tarefas = $tarefas->fetchAll();
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

  <a href="logout.php" class="btn-logout">Sair</a>

  <div class="filtros">
    <a href="index.php?filtro=todas"
      class="btn-filtro <?php echo $filtro === 'todas' ? 'ativo' : ''; ?>">
      Todas
    </a>

    <a href="index.php?filtro=pendentes"
      class="btn-filtro <?php echo $filtro === 'pendentes' ? 'ativo' : ''; ?>">
      pendentes
    </a>

    <a href="index.php?filtro=concluidas"
      class="btn-filtro <?php echo $filtro === 'concluidas' ? 'ativo' : ''; ?>">
      concluidas
    </a>
  </div>

  <form method="POST">
    <input type="text" name="tarefa" placeholder="Digite uma tarefa...">
    <button type="submit" class="btn-adicionar">Adicionar</button>
  </form>

  <ul>
    <?php if (empty($tarefas)): ?>
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
            <form method="POST" style="display:inline">
              <input type="hidden" name="concluir" value="<?php echo $tarefa['id']; ?>">
              <button type="submit">
                <?php echo $tarefa['concluida'] ? '✅' : '☐'; ?>
              </button>
            </form>
            <span class="<?php echo $tarefa['concluida'] ? 'concluida' : ''; ?>">
              <?php echo htmlspecialchars($tarefa['descricao']); ?>
            </span>
            <span class="data-criacao">
              <?php echo date('d/m/Y H:i', strtotime($tarefa['created_at']));?>
            </span>
            <a href="index.php?editar=<?php echo $tarefa['id']; ?>">✏️</a>
            <form method="POST" style="display:inline">
              <input type="hidden" name="deletar" value="<?php echo $tarefa['id']; ?>">
              <button type="submit" class="btn-deletar">🗑️</button>
            </form>
          <?php endif; ?>
        </li>
      <?php endforeach; ?>
    <?php endif; ?>
  </ul>

</body>
</html>