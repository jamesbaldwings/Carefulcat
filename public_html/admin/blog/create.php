<?php
require_once __DIR__.'/../../includes/config.php';
require_once __DIR__.'/../../includes/db.php';
require_once __DIR__.'/../../includes/functions.php';
requireAdmin();
$page_title='New Post';
$errors=[];
if($_SERVER['REQUEST_METHOD']==='POST'){
  if(!csrf_verify($_POST['csrf']??'')){ $errors[]='Invalid CSRF token.'; }
  $title=trim($_POST['title']??''); $slug=trim($_POST['slug']??''); $body=trim($_POST['body']??''); $status=$_POST['status']??'draft';
  if($title===''||$slug===''||$body===''){ $errors[]='Title, slug, and body are required.'; }
  if(!$errors){
    $pub = $status==='published' ? date('Y-m-d H:i:s') : null;
    db()->query("INSERT INTO blog_posts(title,slug,body,status,created_at,published_at) VALUES(?,?,?,?,NOW(),?)",[$title,$slug,$body,$status,$pub]);
    flash('success','Post created.'); redirect('/admin/blog/index.php');
  }
}
require_once __DIR__.'/../includes/admin-header.php';
?>
<div class="admin-card">
  <div class="admin-card-header"><h2 class="admin-card-title">📰 New Post</h2></div>
  <?php if($errors):?><div class="alert alert-error"><?php echo htmlspecialchars(implode(' ',$errors));?></div><?php endif;?>
  <form method="post">
    <input type="hidden" name="csrf" value="<?php echo csrf_token();?>">
    <div class="form-group"><label>Title</label><input name="title" required></div>
    <div class="form-group"><label>Slug</label><input name="slug" required></div>
    <div class="form-group"><label>Body</label><textarea name="body" rows="10" required></textarea></div>
    <div class="form-group"><label>Status</label>
      <select name="status"><?php foreach(['draft','published'] as $s):?><option value="<?php echo $s;?>"><?php echo ucfirst($s);?></option><?php endforeach;?></select>
    </div>
    <button class="btn" type="submit">Save</button>
    <a class="btn btn-outline" href="/admin/blog/index.php">Cancel</a>
  </form>
</div>
<?php require_once __DIR__.'/../includes/admin-footer.php'; ?>
