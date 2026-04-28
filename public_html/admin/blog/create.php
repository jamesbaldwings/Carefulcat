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
  <div class="admin-card-header">
    <h1 class="admin-card-title">📰 Create New Blog Post</h1>
  </div>
  <div class="admin-card-body">
    <?php if (!empty($errors)): ?>
      <div class="alert alert-error">
        <?php foreach ($errors as $error): ?>
          <p><?php echo htmlspecialchars($error); ?></p>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
    <form method="post">
      <input type="hidden" name="csrf" value="<?php echo csrf_token(); ?>">

      <div class="form-section">
        <h2 class="form-section-title">Post Content</h2>
        <div class="form-group">
          <label for="title">Title <span class="required">*</span></label>
          <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>" placeholder="Enter post title..." required>
        </div>
        <div class="form-group">
          <label for="slug">Slug <span class="required">*</span></label>
          <input type="text" id="slug" name="slug" value="<?php echo htmlspecialchars($_POST['slug'] ?? ''); ?>" placeholder="a-unique-url-slug" required>
          <small class="form-hint">The URL-friendly version of the title.</small>
        </div>
        <div class="form-group">
          <label for="body">Body <span class="required">*</span></label>
          <textarea id="body" name="body" rows="10" required placeholder="Write your post content here..."><?php echo htmlspecialchars($_POST['body'] ?? ''); ?></textarea>
        </div>
      </div>

      <div class="form-section">
        <h2 class="form-section-title">Publishing</h2>
        <div class="form-group">
          <label for="status">Status</label>
          <select id="status" name="status">
            <?php 
            $current_status = $_POST['status'] ?? 'draft';
            foreach (['draft', 'published'] as $s):
            ?>
              <option value="<?php echo $s; ?>" <?php if ($s === $current_status) echo 'selected'; ?>><?php echo ucfirst($s); ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn btn-primary">Save Post</button>
        <a href="/admin/blog/index.php" class="btn btn-outline">Cancel</a>
      </div>
    </form>
  </div>
</div>
<?php require_once __DIR__.'/../includes/admin-footer.php'; ?>
