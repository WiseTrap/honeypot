<footer class="fixed-bottom bg-body-tertiary py-1 shadow border-top d-block d-print-none">
    <div class="container-fluid text-center">
        <div class="row align-items-center justify-content-between">
            <div class="col-auto">
                <small>
                    <a class="navbar-brand" href="<?= url('about'); ?>">
                        <?= config('WISE.name') . " &copy; " . date('Y') . " " . config('WISE.version'); ?>
                    </a>
                </small>
            </div>
            <div class="col-auto">
                <nav class="col">
                    <ul class="list-inline mb-0">
                        <li class="list-inline-item">
                            <a class="nav-link active" href="#" data-bs-toggle="modal" data-bs-target="#exampleModal"><?= svg('translate') . 'العربيه'; ?></a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</footer>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" dir="rtl">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">رسالة إدارية</h1>
                <button type="button" class="btn-close ms-0 me-auto" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                نود إعلامكم بأن دعم اللغة العربية لا يزال قيد التطوير حاليا،
                ونعمل باستمرار على تحسين التجربة وتوفير أفضل أداء ممكن.
                <br>
                <br>
                <b class="text-success">البتراء لم تبن في يوم واحد!.</b>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>