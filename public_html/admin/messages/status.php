<?php
require_once __DIR__.'/../../includes/config.php';
require_once __DIR__.'/../../includes/db.php';
require_once __DIR__.'/../../includes/functions.php';
requireAdmin();
if($_SERVER['REQUEST_METHOD']!=='POST'){ redirect('/admin/messages/index.php'); }
if(!csrf_verify($_POST['csrf']??'')){ flash('error','Invalid CSRF.'); redirect('/admin/messages/index.php'); }
$id=(int)($_POST['id']??0);
$status=$_POST['status']??'unread';
if($id>0 && in_array($status,['unread','read','archived'],true)){
  db()->query("UPDATE contacts SET status=? WHERE id=?",[$status,$id]);
  flash('success','Message updated.');
}
redirect('/admin/messages/index.php');
