<?php if(!app()->environment('local')): ?>
    <?php echo abort(404); ?>

<?php endif; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Test POST</title>
</head>
<body>
    <h1>Test POST Request</h1>
    <form action="/test-post" method="POST">
        <?php echo csrf_field(); ?>
        <input type="text" name="test_field" value="test_value">
        <button type="submit">Submit Test POST</button>
    </form>

    <h2>Test Login POST</h2>
    <form action="<?php echo e(route('center.login.submit')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <label>Email:</label>
        <input type="email" name="email" value="final@test.com"><br>
        <label>Password:</label>
        <input type="password" name="password" value="password123"><br>
        <button type="submit">Submit Login</button>
    </form>

    <h2>Debug Info</h2>
    <p>CSRF Token: <?php echo e(csrf_token()); ?></p>
    <p>Route Exists: <?php echo e(Route::has('center.login.submit') ? 'YES' : 'NO'); ?></p>
    <p>Generated Route URL: <?php echo e(route('center.login.submit')); ?></p>
</body>
</html>
<?php /**PATH D:\new project\antigravty\edu\edu\resources\views\test-post.blade.php ENDPATH**/ ?>