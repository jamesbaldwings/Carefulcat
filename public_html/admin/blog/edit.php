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
    $pub = $status==='published' ? ( ($p['published_at'] ?? null) ?: date('Y-m-d H:i:s') ) : null;
    db()->query("UPDATE blog_posts SET title=?,slug=?,body=?,status=?,published_at=? WHERE id=?",[$title,$slug,$body,$status,$pub,$id]);
    flash('success','Post updated.'); redirect('/admin/blog/index.php');
  }
}
require_once __DIR__.'/../includes/admin-header.php';
?>
<div class="admin-card">
  <div class="admin-card-header">
    <h1 class="admin-card-title">📰 Edit Post</h1>
  </div>
  <div class="admin-card-body">
    <?php if($errors):?><div class="alert alert-error"><?php echo htmlspecialchars(implode(' ',$errors));?></div><?php endif;?>
    <form method="post">
      <input type="hidden" name="csrf" value="<?php echo csrf_token();?>">
      
      <div class="form-section">
        <h2 class="form-section-title">Post Content</h2>
        <div class="form-group">
          <label for="title">Title <span class="required">*</span></label>
          <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($p['title'] ?? '');?>" placeholder="Enter post title..." required>
        </div>
        <div class="form-group">
          <label for="slug">Slug <span class="required">*</span></label>
          <input type="text" id="slug" name="slug" value="<?php echo htmlspecialchars($p['slug'] ?? '');?>" placeholder="e.g., my-awesome-post" required>
        </div>
        <div class="form-group">
          <label for="body">Body <span class="required">*</span></label>
          <textarea id="body" name="body" rows="10" required placeholder="Write your post content here..."><?php echo htmlspecialchars($p['body'] ?? '');?></textarea>
        </div>
      </div>

      <div class="form-section">
        <h2 class="form-section-title">Status & Options</h2>
        <div class="form-group">
          <label for="status">Status</label>
          <select id="status" name="status">
            <?php foreach(['draft','published'] as $s):?>
              <option value="<?php echo $s;?>" <?php echo ($p['status']??'')===$s?'selected':'';?>><?php echo ucfirst($s);?></option>
            <?php endforeach;?>
          </select>
        </div>
      </div>

      <div class="form-actions">
        <button class="btn btn-primary" type="submit">Save Changes</button>
        <a class="btn btn-outline" href="/admin/blog/index.php">Cancel</a>
      </div>
    </form>
  </div>
</div>
<?php require_once __DIR__.'/../includes/admin-footer.php'; ?>
