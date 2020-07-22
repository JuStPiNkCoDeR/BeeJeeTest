<!-- Header with sign in for admin -->
<header>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-8">
                <h1>Task list</h1>
            </div>
            <?php if (!$data['isAdmin']): ?>
                <div class="col-4">
                    <a href="/index/signIn">Sign in</a>
                </div>
            <?php else: ?>
                <div class="col-4">
                    <a href="/index/logOut">Log out</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</header>
<!-- Create task form and sort form -->
<div class="container">
    <div class="row justify-content-around">
        <div class="col-4">
            <form action="/index/create" method="post">
                <label for="input-username">
                    Username
                    <input id="input-username" type="text" name="username" placeholder="Type your name..." required>
                </label>
                <label for="input-email">
                    Email
                    <input id="input-email" type="text" name="email" placeholder="Type your email..." pattern="^.+@.+$"
                           required>
                </label>
                <label for="input-description">
                    Description
                    <textarea name="description" id="input-description" placeholder="Type your task..."
                              required></textarea>
                </label>
                <button>Create task</button>
            </form>
        </div>
        <div class="col-4">
            <form action="<?= '/?page=' . $data['page'] ?>" method="get">
                <label for="input-order-by">
                    Order by
                    <select name="orderBy" id="input-order-by">
                        <option value="ID" selected>Default way</option>
                        <option value="username">Username</option>
                        <option value="email">Email</option>
                        <option value="isCompleted">Status</option>
                    </select>
                </label>
                <label for="input-order-direction">
                    Order direction
                    <select name="orderDirection" id="input-order-direction">
                        <option value="ASC" selected>Increment</option>
                        <option value="DESC">Decrease</option>
                    </select>
                </label>
                <button>Sort</button>
            </form>
        </div>
    </div>
</div>
<!-- 3 tasks block -->
<div class="container">
    <div class="row justify-content-around">
        <?php foreach ($data['tasks'] as $taskData) { ?>
            <div class="col-4">
                <div class="taskBlock">
                    <p>Name: <?= $taskData['username'] ?></p>
                    <p>Email: <?= $taskData['email'] ?></p>
                    <?php if (!$data['isAdmin']): ?>
                        <span><?= $taskData['description'] ?></span>
                    <?php else: ?>
                        <form action="<?= '/index/update?page=' . $data['page'] ?>" method="post">
                            <label for="admin-input-description">
                                Description
                                <textarea name="description"
                                          id="admin-input-description"><?= $taskData['description'] ?></textarea>
                            </label>
                            <label for="admin-input-completed">
                                Completed
                                <input id="admin-input-completed" name="completed" value="true"
                                       type="checkbox" <?= $taskData['isCompleted'] == 1 ? 'checked' : '' ?>>
                            </label>
                            <input type="hidden" name="ID" value=<?= $taskData['ID'] ?>>
                            <button>Save</button>
                        </form>
                    <?php endif; ?>
                    <?php if ($taskData['isCompleted'] == 1): ?>
                        <span class="completed">&#10004;</span>
                    <?php endif; ?>
                    <?php if ($taskData['editedByAdmin'] == 1): ?>
                        <span class="edited">&#9998;</span>
                    <?php endif; ?>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
<!-- Pagination -->
<div class="container">
    <div class="paginationBlock">
        <div class="row justify-content-around">
            <div class="col-4">
                <?php
                if ($data['page'] - 1 != 0) {
                    echo
                        '<div class="row justify-content-center">
                    <a href="/?page=' . ($data['page'] - 1) . '&orderBy=' . $data['orderBy'] . '&orderDirection=' . $data['orderDirection'] . '">Previous</a>
                 </div>';
                }
                ?>
            </div>
            <div class="col-4">
                <div class="row justify-content-center">
                    <span><?= $data['page'] ?></span>
                </div>
            </div>
            <div class="col-4">
                <?php
                if ($data['page'] + 1 <= $data['pagesAmount']) {
                    echo
                        '<div class="row justify-content-center">
                    <a href="/?page=' . ($data['page'] + 1) . '&orderBy=' . $data['orderBy'] . '&orderDirection=' . $data['orderDirection'] . '">Next</a>
                 </div>';
                }
                ?>
            </div>
        </div>
    </div>
</div>