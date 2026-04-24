<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

requireAdmin();
// Authentication handled by requireAdmin()

$page_title = 'Add New Cat';
include '../includes/admin-header.php';
?>

<div class="admin-card">
    <div class="admin-card-header">
        <h2 class="admin-card-title">Add New Cat</h2>
        <a href="index.php" class="admin-btn admin-btn-secondary">Back to List</a>
    </div>
    
    <form method="POST" enctype="multipart/form-data">
        <div class="admin-form-group">
            <label class="admin-form-label">Name *</label>
            <input type="text" name="name" class="admin-form-control" required>
        </div>
        
        <div class="admin-form-group">
            <label class="admin-form-label">Age *</label>
            <select name="age" class="admin-form-control" required>
                <option value="Kitten">Kitten</option>
                <option value="Young Adult">Young Adult</option>
                <option value="Adult">Adult</option>
                <option value="Senior">Senior</option>
            </select>
        </div>
        
        <div class="admin-form-group">
            <label class="admin-form-label">Sex *</label>
            <select name="sex" class="admin-form-control" required>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
        </div>
        
        <div class="admin-form-group">
            <label class="admin-form-label">Breed</label>
            <input type="text" name="breed" class="admin-form-control">
        </div>
        
        <div class="admin-form-group">
            <label class="admin-form-label">Color</label>
            <input type="text" name="color" class="admin-form-control">
        </div>
        
        <div class="admin-form-group">
            <label class="admin-form-label">Status *</label>
            <select name="status" class="admin-form-control" required>
                <option value="available">Available</option>
                <option value="pending">Pending</option>
                <option value="adopted">Adopted</option>
                <option value="not_available">Not Available</option>
            </select>
        </div>
        
        <div class="admin-form-group">
            <label class="admin-form-label">Adoption Fee *</label>
            <input type="number" name="adoption_fee" class="admin-form-control" step="0.01" required>
        </div>
        
        <div class="admin-form-group">
            <label class="admin-form-label">Description</label>
            <textarea name="description" class="admin-form-control" rows="5"></textarea>
        </div>
        
        <div class="admin-form-group">
            <label class="admin-form-label">Medical History</label>
            <textarea name="medical_history" class="admin-form-control" rows="4"></textarea>
        </div>
        
        <div class="admin-form-group">
            <label class="admin-form-label">Personality Traits</label>
            <input type="text" name="personality_traits" class="admin-form-control" placeholder="Friendly, Playful, Calm (comma-separated)">
        </div>
        
        <div class="admin-form-group">
            <label class="admin-form-label">Image</label>
            <input type="file" name="image" class="admin-form-control" accept="image/*">
        </div>
        
        <button type="submit" class="admin-btn admin-btn-primary">Add Cat</button>
    </form>
</div>

<?php include '../includes/admin-footer.php'; ?>