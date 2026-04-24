<?php
// edit.php
require_once __DIR__.'/../../includes/config.php';
require_once __DIR__.'/../../includes/db.php';
require_once __DIR__.'/../../includes/functions.php';
requireAdmin();
$id=(int)($_GET['id']??0);
$p=db()->fetchOne("SELECT * FROM blog_posts WHERE id=?",[$id]);
if(!$p){ redirect('/admin/blog/index.php'); }
$page_title='Edit Post';
$errors=[];
if($_SERVER['REQUEST_METHOD']==='POST'){
  if(!csrf_verify($_POST['csrf']??'')){ $errors[]='Invalid CSRF token.'; }
  $title=trim($_POST['title']??''); $slug=trim($_POST['slug']??''); $body=trim($_POST['body']??''); $status=$_POST['status']??'draft';
  if($title===''||$slug===''||$body===''){ $errors[]='Title, slug, and body are required.'; }
  if(!$errors){
    $pub = $status==='published' ? ( $p['published_at'] ?: date('Y-m-d H:i:s') ) : null;
    db()->query("UPDATE blog_posts SET title=?,slug=?,body=?,status=?,published_at=? WHERE id=?",[$title,$slug,$body,$status,$pub,$id]);
    flash('success','Post updated.'); redirect('/admin/blog/index.php');
  }
}
require_once __DIR__.'/../includes/admin-header.php';
?>
<div class="admin-card">
  <div class="admin-card-header"><h2 class="admin-card-title">📰 Edit Post</h2></div>
  <?php if($errors):?><div class="alert alert-error"><?php echo htmlspecialchars(implode(' ',$errors));?></div><?php endif;?>
  <form method="post">
    <input type="hidden" name="csrf" value="<?php echo csrf_token();?>">
    <div class="form-group"><label>Title</label><input name="title" value="<?php echo htmlspecialchars($p['title']);?>" required></div>
    <div class="form-group"><label>Slug</label><input name="slug" value="<?php echo htmlspecialchars($p['slug']);?>" required></div>
    <div class="form-group"><label>Body</label><textarea name="body" rows="10" required><?php echo htmlspecialchars($p['body']);?></textarea></div>
    <div class="form-group"><label>Status</label>
      <select name="status"><?php foreach(['draft','published'] as $s):?>
        <option value="<?php echo $s;?>" <?php echo $p['status']===$s?'selected':'';?>><?php echo ucfirst($s);?></option>
      <?php endforeach;?></select>
    </div>
    <button class="btn" type="submit">Save</button>
    <a class="btn btn-outline" href="/admin/blog/index.php">Cancel</a>
  </form>
</div>
<?php require_once __DIR__.'/../includes/admin-footer.php'; ?>
