<div class="container">
    <div class="row">
        <div class="col-4">
            <form action="/index/admin" method="post">
                <label for="input-login">
                    Login
                    <input id="input-login" type="text" name="login" placeholder="Type your login..." required>
                </label>
                <label for="input-password">
                    Password
                    <input id="input-password" type="text" name="password" placeholder="Type your password..." required>
                </label>
                <button>Sign in</button>
            </form>
            <?php if ($data == 'denied'): ?>
                Access denied.
            <?php endif; ?>
        </div>
    </div>
</div>