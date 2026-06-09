<?php
require_once 'db.php';
$usuarioId = $_SESSION['usuario_id'];

// Adicionar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tarefa'])) {
  $novaTarefa = $_POST['tarefa'];
  if (!empty($novaTarefa)) {
    $stmt = $db->prepare('INSERT INTO tarefas (descricao, usuario_id) VALUES (:descricao, :usuario_id)');
    $stmt->execute([':descricao' => $novaTarefa, ':usuario_id' => $usuarioId]);
  }
  echo "<script>window.location='index.php';</script>";
  exit;
}

// Concluir
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['concluir'])) {
  $stmt = $db->prepare('UPDATE tarefas SET concluida = CASE WHEN concluida = 0 THEN 1 ELSE 0 END WHERE id = :id AND usuario_id = :usuario_id');
  $stmt->execute([':id' => $_POST['concluir'], ':usuario_id' => $usuarioId]);
  echo "<script>window.location='index.php';</script>";
  exit;
}

// Deletar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deletar'])) {
  $stmt = $db->prepare('DELETE FROM tarefas WHERE id = :id AND usuario_id = :usuario_id');
  $stmt->execute([':id' => $_POST['deletar'], ':usuario_id' => $usuarioId]);
  echo "<script>window.location='index.php';</script>";
  exit;
}

// Editar 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar_id'])) {
  $stmt = $db->prepare('UPDATE tarefas SET descricao = :descricao WHERE id = :id AND usuario_id = :usuario_id');
  $stmt->execute([':descricao' => $_POST['editar_descricao'], ':id' => $_POST['editar_id'], ':usuario_id' => $usuarioId]);
  echo "<script>window.location='index.php';</script>";
  exit;
}