<?php
/** @var $user */
?>
<img src="<?= url('assets/img/wisetrap.png'); ?>" class="mx-auto d-block" height="200" alt="wisetrap" title="wisetrap">
<div class="d-flex justify-content-center mt-3">
    <div class="card bg-body-tertiary cardCyber">
        <div class="card-body rounded">
            <form action="<?= e('/auth'); ?>" method="POST">
                <div class="form-floating mb-3">
                    <input name="username" value="<?= e($user->username); ?>" type="text" class="form-control <?= $user->hasError('username') ? 'is-invalid' : ''; ?>" id="floatingUsername" aria-describedby="UsernameFeedback" placeholder="Username">
                    <label for="floatingUsername"><?= 'Username'; ?></label>
                    <div id="UsernameFeedback" class="invalid-feedback text-center"><?= $user->getError('username'); ?></div>
                </div>
                <div class="form-floating">
                    <input name="password" type="password" class="form-control <?= $user->hasError('password') ? 'is-invalid' : ''; ?>" id="floatingPassword" aria-describedby="PasswordFeedback" placeholder="Password" autocomplete="off">
                    <label for="floatingPassword"><?= 'Password'; ?></label>
                    <div id="PasswordFeedback" class="invalid-feedback text-center"><?= $user->getError('password'); ?></div>
                </div>
                <div class="d-grid gap-2 col-6 mx-auto">
                    <button type="submit" class="btn btn-primary mt-3 mx-auto">
                        <?= 'Login'; ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="d-flex justify-content-center mt-3">
    <div class="card cardCyber">
        <div class="card-body">
            <small><?= config('WISE.version'); ?></small>
            <div class="float-end">
                <div class="dropdown-center">
                    <button class="btn btn-link nav-link dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <svg width="16" height="16" class="bi my-1 theme-icon-active"><use href="#circle-half"></use></svg>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end py-0" aria-labelledby="bd-theme">
                        <li>
                            <button type="button" class="dropdown-item d-flex align-items-center rounded-top" data-bs-theme-value="light">
                                <?= svg('light').'Light'; ?>
                            </button>
                        </li>
                        <li>
                            <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark">
                                <?= svg('dark').'Dark'; ?>
                            </button>
                        </li>
                        <li>
                            <button type="button" class="dropdown-item d-flex align-items-center rounded-bottom" data-bs-theme-value="auto">
                                <?= svg('auto').'Auto'; ?>
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<p class="text-danger text-center"><small><?= 'Log in to the control panel'; ?></p>