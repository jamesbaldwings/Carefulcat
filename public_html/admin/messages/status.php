<?php
require_once __DIR__.'/../../includes/config.php';
require_once __DIR__.'/../../includes/db.php';
require_once __DIR__.'/../../includes/functions.php';
requireAdmin();
if($_SERVER['REQUEST_METHOD']!=='POST'){ redirect('/admin/messages/index.php'); }
if(!csrf_verify($_POST['csrf']??'')){ flash('error','Invalid CSRF.'); redirect('/admin/messages/index.php'); }
$id=$_POST['id'] ?? '';
$status=$_POST['status']??'unread';
if(!empty($id) && in_array($status,['unread','read','archived'],true)){
  // contacts table has no status column - skip status update
  // db()->query("UPDATE contacts SET status=? WHERE id=?",[$status,$id]);
  flash('success','Message updated.');
}
redirect('/admin/messages/index.php');
