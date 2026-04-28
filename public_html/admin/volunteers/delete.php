<?php
require_once __DIR__.'/../../includes/config.php';
require_once __DIR__.'/../../includes/db.php';
require_once __DIR__.'/../../includes/functions.php';
requireAdmin();
if($_SERVER['REQUEST_METHOD']!=='POST'){ redirect('/admin/volunteers/index.php'); }
if(!csrf_verify($_POST['csrf'] ?? '')){ flash('error','Invalid CSRF.'); redirect('/admin/volunteers/index.php'); }
$id=$_POST['id'] ?? '';
if(!empty($id)){ db()->query("DELETE FROM volunteers WHERE id=?",[$id]); flash('success','Volunteer deleted.'); }
redirect('/admin/volunteers/index.php');
