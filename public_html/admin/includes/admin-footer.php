<?php
$ASSETS = defined('ASSETS_URL') ? ASSETS_URL : '/assets';
$SITE_NAME = defined('SITE_NAME') ? SITE_NAME : 'Site';
?>
            </div><!-- /.admin-content -->

            <footer class="admin-footer">
                <small>&copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($SITE_NAME); ?></small>
            </footer>
        </main>
    </div><!-- /.admin-layout -->

    <script src="<?php echo $ASSETS; ?>/js/admin.js"></script>
</body>
</html>
