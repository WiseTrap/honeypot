<div class="col-lg-1 col-sm-2 col-2 bg-body-tertiary shadow border-end cyberSide text-center d-block d-print-none">
    <div class="position-sticky">
        <ul class="nav navbar-nav nav-pills flex-column">
            <li class="nav-item">
                <a class="nav-link px-0 rounded-0 <?= isActive('dashboard') || isActive('/') ? 'active text-white' : '' ?>" href="<?= url('dashboard'); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-speedometer2" viewBox="0 0 16 16">
                        <path d="M8 4a.5.5 0 0 1 .5.5V6a.5.5 0 0 1-1 0V4.5A.5.5 0 0 1 8 4M3.732 5.732a.5.5 0 0 1 .707 0l.915.914a.5.5 0 1 1-.708.708l-.914-.915a.5.5 0 0 1 0-.707M2 10a.5.5 0 0 1 .5-.5h1.586a.5.5 0 0 1 0 1H2.5A.5.5 0 0 1 2 10m9.5 0a.5.5 0 0 1 .5-.5h1.5a.5.5 0 0 1 0 1H12a.5.5 0 0 1-.5-.5m.754-4.246a.39.39 0 0 0-.527-.02L7.547 9.31a.91.91 0 1 0 1.302 1.258l3.434-4.297a.39.39 0 0 0-.029-.518z"/>
                        <path fill-rule="evenodd" d="M0 10a8 8 0 1 1 15.547 2.661c-.442 1.253-1.845 1.602-2.932 1.25C11.309 13.488 9.475 13 8 13c-1.474 0-3.31.488-4.615.911-1.087.352-2.49.003-2.932-1.25A8 8 0 0 1 0 10m8-7a7 7 0 0 0-6.603 9.329c.203.575.923.876 1.68.63C4.397 12.533 6.358 12 8 12s3.604.532 4.923.96c.757.245 1.477-.056 1.68-.631A7 7 0 0 0 8 3"/>
                    </svg>
                    <span class="d-none d-sm-block"><?= 'Dashboard'; ?></span>
                </a>
            </li>
            <?php if (hasRole('admin')): ?>
                <li class="nav-item">
                    <a class="nav-link px-0 rounded-0 <?= isActive('attackers') ? 'active text-white' : '' ?>" href="<?= url('attackers'); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-bootstrap-icon" viewBox="0 0 512 512">
                            <path d="M378.625,209.465c-6.531-38.344-13.672-80-15.844-91.844c-5.313-28.906-43.375-45.063-71.656-24.234 c-14.828,10.938-28.094,11.719-35.125,11.719s-14.828,1.563-35.125-11.719c-29.391-19.219-66.344-4.672-71.656,24.234 c-2.172,11.844-9.313,53.5-15.844,91.844C53.906,219.418,0,238.778,0,261.012c0,32.438,114.625,58.719,256,58.719 c141.391,0,256-26.281,256-58.719C512,238.778,458.094,219.418,378.625,209.465z"/>
                            <path d="M109.125,330.45l7.547,86.515c39.563,6.719,79.734,10.219,119.703,11.078L256,401.278l19.625,26.765 c39.969-0.859,80.141-4.359,119.703-11.078l7.547-86.515c-48.375,9.359-97.906,13.5-146.875,13.5 C207.016,343.95,157.516,339.809,109.125,330.45z M186.688,401.997c-33.469-1.578-35.563-41.766-35.563-41.766l75.125,14.672 C226.25,374.903,220.156,403.59,186.688,401.997z M360.875,360.231c0,0-2.094,40.188-35.563,41.766 c-33.469,1.594-39.563-27.094-39.563-27.094L360.875,360.231z"/>
                        </svg>
                        <span class="d-none d-sm-block"><?= 'Attackers'; ?></span>
                    </a>
                </li>
            <?php endif; ?>
            <?php if (hasRole('loginTrap') || hasRole('SQLInjection')): ?>
            <li class="nav-item">
                <a class="nav-link px-0 rounded-0 <?= isActive('customers') ? 'active text-white' : '' ?>" href="<?= url('customers'); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-people-fill" viewBox="0 0 16 16">
                        <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/>
                    </svg>
                    <span class="d-none d-sm-block"><?= 'Customers'; ?></span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link px-0 rounded-0 <?= isActive('price_offers') ? 'active text-white' : '' ?>" href="<?= url('price_offers'); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-percent" viewBox="0 0 16 16">
                        <path d="M13.442 2.558a.625.625 0 0 1 0 .884l-10 10a.625.625 0 1 1-.884-.884l10-10a.625.625 0 0 1 .884 0M4.5 6a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m0 1a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5m7 6a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m0 1a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/>
                    </svg>
                    <span class="d-none d-sm-block"><?= 'Price Offers'; ?></span>
                </a>
            </li>
            <?php endif; ?>
        </ul>
    </div>
</div>