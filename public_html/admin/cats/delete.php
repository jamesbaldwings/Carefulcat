<?php
require_once __DIR__.'/../../includes/config.php';
require_once __DIR__.'/../../includes/db.php';
require_once __DIR__.'/../../includes/functions.php';
requireAdmin();

if($_SERVER['REQUEST_METHOD']!=='POST'){ redirect('/admin/cats/index.php'); exit; }
if(!csrf_verify($_POST['csrf'] ?? '')){ flash('error','Invalid CSRF.'); redirect('/admin/cats/index.php'); exit; }

$id=$_POST['id'] ?? '';
if(!empty($id)){ db()->query("DELETE FROM cats WHERE id=?",[$id]); flash('success','Cat deleted.'); }
redirect('/admin/cats/index.php'); exit;
