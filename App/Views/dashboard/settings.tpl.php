<div class="card">
    <div class="card-header">System Updates</div>
    <div class="card-body">
        <div class="alert alert-secondary mb-3" id="update-status">Click "Check For Updates"</div>
        <div class="btn-group">
            <button type="button" class="btn btn-primary" id="check-updates-btn">Check For Updates</button>
            <button type="button" class="btn btn-success" id="install-updates-btn" disabled>Install Update</button>
        </div>
    </div>
</div>

<script>
    const checkButton   = document.getElementById('check-updates-btn');
    const installButton = document.getElementById('install-updates-btn');
    const statusBox     = document.getElementById('update-status');
    /**
     * CHECK
     */
    checkButton.addEventListener('click', async () => {
        installButton.disabled  = true;
        statusBox.className     = 'alert alert-info mb-3';
        statusBox.textContent   = 'Checking for updates...';
        try {
            const response  = await fetch('/settings/check-updates');
            const data      = await response.json();
            if (!data.success) {
                statusBox.className     = 'alert alert-danger mb-3';
                statusBox.textContent   = data.message;
                return;
            }
            if (data.has_update) {
                statusBox.className     = 'alert alert-warning mb-3';
                statusBox.textContent   = `Update available (${data.current_version} → ${data.latest_version})`;
                installButton.disabled  = false;
            } else {
                statusBox.className     = 'alert alert-success mb-3';
                statusBox.textContent   = 'System is up to date.';
            }
        } catch (e) {
            statusBox.className         = 'alert alert-danger mb-3';
            statusBox.textContent       = 'Check failed.';
        }

    });
    /**
     * INSTALL
     */
    installButton.addEventListener('click', async () => {
        installButton.disabled  = true;
        statusBox.className     = 'alert alert-info mb-3';
        statusBox.textContent   = 'Installing update...';
        try {
            const response      = await fetch('/settings/install-update', {
                method: 'POST'
            });
            const data  = await response.json();
            if (!data.success) {
                statusBox.className     = 'alert alert-danger mb-3';
                statusBox.textContent   = data.message;
                return;
            }
            statusBox.className     = 'alert alert-success mb-3';
            statusBox.textContent   = data.message;
        } catch (e) {
            statusBox.className     = 'alert alert-danger mb-3';
            statusBox.textContent   = 'Install failed.';
        }
    });
</script>