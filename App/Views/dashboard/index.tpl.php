<?php
/** @var $userProfile */
/** @var array $stats */
?>
<div class="row align-items-center mt-2">
    <div class="col-12 col-md-auto order-md-1 d-flex align-items-center justify-content-center mb-4 mb-md-0">
        <div class="text-info me-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="fs-4 duo-icon duo-icon-world" data-duoicon="world"><path fill="currentColor" d="M12 2c5.523 0 10 4.477 10 10s-4.477 10-10 10S2 17.523 2 12 6.477 2 12 2Z" class="duoicon-secondary-layer" opacity=".3"></path><path fill="currentColor" d="M12 4a7.988 7.988 0 0 0-6.335 3.114l-.165.221V9.02c0 1.25.775 2.369 1.945 2.809l.178.06 1.29.395c1.373.42 2.71-.697 2.577-2.096l-.019-.145-.175-1.049a1 1 0 0 1 .656-1.108l.108-.03.612-.14a2.667 2.667 0 0 0 1.989-3.263A7.987 7.987 0 0 0 12 4Zm2 9.4-1.564 1.251a.5.5 0 0 0-.041.744l1.239 1.239c.24.24.415.538.508.864l.175.613c.147.521.52.948 1.017 1.163a8.026 8.026 0 0 0 2.533-1.835l-.234-1.877a2 2 0 0 0-1.09-1.54l-1.47-.736A1 1 0 0 0 14 13.4Z" class="duoicon-primary-layer"></path></svg>
        </div>
        <?= 'Hashemite Kingdom'; ?> –&nbsp;<time datetime="<?= date("h:i");?>"><?= date("h:i:sa");?></time>
    </div>
    <div class="col-12 col-md order-md-0 text-center text-md-start">
        <h1><?= 'Hello, '; ?> <?= e($userProfile->FirstName_En) . ' ' . e($userProfile->LastName_En) ?></h1>
        <p class="fs-lg text-body-secondary mb-0"><?= 'The following is a summary of the wise trap activities.'; ?></p>
    </div>
</div>

<hr class="my-4">

<div class="row mb-8">
    <div class="col-12 col-md-6 col-xxl-3 mb-4 mb-xxl-0">
        <div class="card bg-body-tertiary border-transparent">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="fs-sm fw-normal text-body-secondary mb-1"><?= 'Attackers'; ?></h4>
                        <div class="fs-4 fw-semibold"><?= e($stats['attackers']); ?></div>
                    </div>
                    <div class="col-auto">
                        <div class="WISEColor">
                            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-bootstrap-icon" viewBox="0 0 512 512">
                                <path d="M378.625,209.465c-6.531-38.344-13.672-80-15.844-91.844c-5.313-28.906-43.375-45.063-71.656-24.234 c-14.828,10.938-28.094,11.719-35.125,11.719s-14.828,1.563-35.125-11.719c-29.391-19.219-66.344-4.672-71.656,24.234 c-2.172,11.844-9.313,53.5-15.844,91.844C53.906,219.418,0,238.778,0,261.012c0,32.438,114.625,58.719,256,58.719 c141.391,0,256-26.281,256-58.719C512,238.778,458.094,219.418,378.625,209.465z"/>
                                <path d="M109.125,330.45l7.547,86.515c39.563,6.719,79.734,10.219,119.703,11.078L256,401.278l19.625,26.765 c39.969-0.859,80.141-4.359,119.703-11.078l7.547-86.515c-48.375,9.359-97.906,13.5-146.875,13.5 C207.016,343.95,157.516,339.809,109.125,330.45z M186.688,401.997c-33.469-1.578-35.563-41.766-35.563-41.766l75.125,14.672 C226.25,374.903,220.156,403.59,186.688,401.997z M360.875,360.231c0,0-2.094,40.188-35.563,41.766 c-33.469,1.594-39.563-27.094-39.563-27.094L360.875,360.231z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-xxl-3 mb-4 mb-xxl-0">
        <div class="card bg-body-tertiary border-transparent">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="fs-sm fw-normal text-body-secondary mb-1"><?= 'Logs'; ?></h4>
                        <div class="fs-4 fw-semibold"><?= e($stats['logs']); ?></div>
                    </div>
                    <div class="col-auto">
                        <div class="WISEColor">
                            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-icon" viewBox="0 0 72.103 72.103">
                                <path d="M57.617,50.754l-1.368,1.369l-4.731-4.731c9.32-11.418,8.803-28.237-1.848-38.889C38.326-2.835,19.859-2.835,8.518,8.507 C-2.84,19.861-2.838,38.317,8.515,49.668c10.639,10.635,27.466,11.164,38.89,1.848l4.727,4.725l-1.376,1.375l14.489,14.487 l6.857-6.859L57.617,50.754z M45.058,48.457c-9.899,8.146-24.548,7.717-33.796-1.53c-9.841-9.834-9.838-25.846,0-35.673 c9.829-9.829,25.833-9.832,35.66-0.004c9.259,9.256,9.689,23.901,1.532,33.811L45.058,48.457z"/>
                                <path d="M32.402,18.231c-4.722-1.366-9.437,1.191-11.003,5.136c-1.697,4.268,1.033,9.78-0.11,12.408 c-1.624,3.74-6.328,9.468,0.11,13.284c5.951,3.52,10.251-2.02,13.365-6.982c2.379-3.801,4.856-7.657,5.828-11.665 C41.345,27.304,41.743,20.939,32.402,18.231z"/>
                                <path d="M23.342,18.446c2.288,0,3.052-1.754,3.052-3.917c0-2.17-1.859-3.927-4.147-3.927c-2.288,0-4.142,1.756-4.142,3.927 C18.103,16.692,21.057,18.446,23.342,18.446z"/>
                                <path d="M30.721,16.746c1.894,0,3.431-2.597,3.431-4.681c0-2.082-1.538-2.859-3.431-2.859c-1.897,0-3.431,0.778-3.431,2.859 C27.291,14.148,28.824,16.746,30.721,16.746z"/>
                                <path d="M40.377,16.564c1.07-1.314,0.5-2.594-0.691-3.57c-1.194-0.974-2.563-1.271-3.633,0.047 c-1.07,1.313-1.435,3.74-0.242,4.709C37.008,18.725,39.31,17.881,40.377,16.564z"/>
                                <path d="M44.569,17.57c-0.965-1.063-2.185-1.532-3.357-0.469c-1.17,1.055-1.85,3.237-0.885,4.309 c0.965,1.07,3.207,0.61,4.38-0.453C45.879,19.901,45.535,18.64,44.569,17.57z"/>
                                <path d="M44.707,22.322c-1.193,0.395-2.389,1.756-2.033,2.844c0.354,1.082,2.136,1.475,3.324,1.087 c1.19-0.396,1.35-1.42,0.994-2.507C46.639,22.663,45.897,21.929,44.707,22.322z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-xxl-3 mb-4 mb-md-0">
        <div class="card bg-body-tertiary border-transparent">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="fs-sm fw-normal text-body-secondary mb-1"><?= 'Traps'; ?></h4>
                        <div class="fs-4 fw-semibold"><?= e($stats['traps']); ?></div>
                    </div>
                    <div class="col-auto">
                        <div class="WISEColor">
                            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-icon" viewBox="0 0 512 512">
                                <path d="M246.253 280.552l71.99 97.834-166.207 48.293zm.274-37.243L138.877 97.006 21 279.83l107.65 146.3 114.21-177.108zm162.63 9.728l34.46-53.457-38.665 11.226v33.426zm-115.097-2.12l-10.515-4.89-18.56 5.388-7.17 11.126 77.33 105.143 31.99-49.628-20.28-42.88zm45.55-88.33h65.405v27.44l44.9-13.06L342.254 30.566 154.83 85.02l107.712 146.39 77.055-22.45v-46.373zm45.45 86.06v-66.105h-25.507v49.49l-13.533-5.1-34.012 10.277 49.89 22.937 104.62 221.287 24.482-7.11z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-xxl-3">
        <div class="card bg-body-tertiary border-transparent">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="fs-sm fw-normal text-body-secondary mb-1"><?= 'Bots' ?></h4>
                        <div class="fs-4 fw-semibold"><?= e($stats['bots']); ?></div>
                    </div>
                    <div class="col-auto">
                        <div class="WISEColor">
                            <svg xmlns="http://www.w3.org/2000/svg" width="70" height="70" fill="currentColor" class="bi bi-icon" viewBox="0 0 100 100">
                                <path d="M49.6,25.8c7.2,0,13,5.8,13,13v3.3c-4.3-0.5-8.7-0.7-13-0.7c-4.3,0-8.7,0.2-13,0.7v-3.3 C36.6,31.7,42.4,25.8,49.6,25.8z"/>
                                <path d="M73.2,63.8l1.3-11.4c2.9,0.5,5.1,2.9,5.1,5.6C79.6,61.2,76.7,63.8,73.2,63.8z"/>
                                <path d="M25.9,63.8c-3.5,0-6.4-2.6-6.4-5.8c0-2.8,2.2-5.1,5.1-5.6L25.9,63.8z"/>
                                <path d="M68.7,44.9c-6.6-0.7-12.9-1-19-1c-6.1,0-12.5,0.3-19,1h0c-2.2,0.2-3.8,2.2-3.5,4.3l2,19.4 c0.2,1.8,1.6,3.3,3.5,3.5c5.6,0.7,11.3,1,17.1,1s11.5-0.3,17.1-1c1.8-0.2,3.3-1.7,3.5-3.5l2-19.4v0 C72.4,47,70.9,45.1,68.7,44.9z M38.6,62.5c-1.6,0-2.8-1.6-2.8-3.7s1.3-3.7,2.8-3.7s2.8,1.6,2.8,3.7S40.2,62.5,38.6,62.5z M55.3,66.6c0,0.2-0.1,0.4-0.2,0.5c-0.1,0.1-0.3,0.2-0.5,0.2h-9.9c-0.2,0-0.4-0.1-0.5-0.2c-0.1-0.1-0.2-0.3-0.2-0.5v-1.8c0-0.4,0.3-0.7,0.7-0.7h0.2c0.4,0,0.7,0.3,0.7,0.7v0.9h8.1v-0.9c0-0.4,0.3-0.7,0.7-0.7h0.2c0.4,0,0.7,0.3,0.7,0.7V66.6z M60.6,62.5c-1.6,0-2.8-1.6-2.8-3.7s1.3-3.7,2.8-3.7s2.8,1.6,2.8,3.7S62.2,62.5,60.6,62.5z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 mb-3">
        <img src="<?= url('assets/img/wisetrap.png'); ?>" class="mx-auto d-block" height="500" alt="WiseTrap" title="WiseTrap">
    </div>
</div>