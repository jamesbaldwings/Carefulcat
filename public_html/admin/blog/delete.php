<?php
// delete.php
require_once __DIR__.'/../../includes/config.php';
require_once __DIR__.'/../../includes/db.php';
require_once __DIR__.'/../../includes/functions.php';
requireAdmin();
if($_SERVER['REQUEST_METHOD']!=='POST'){ redirect('/admin/blog/index.php'); }
if(!csrf_verify($_POST['csrf']??'')){ flash('error','Invalid CSRF.'); redirect('/admin/blog/index.php'); }
$id=$_POST['id'] ?? '';
if(!empty($id)){ db()->query("DELETE FROM blog_posts WHERE id=?",[$id]); flash('success','Post deleted.'); }
redirect('/admin/blog/index.php');
