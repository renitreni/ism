@extends('admin_layout')

@section('styles')
<style>
    .backup-card { transition: all 0.2s; background-color: rgba(255,255,255,0.05) !important; border-color: rgba(255,255,255,0.1) !important; }
    .backup-card:hover { box-shadow: 0 0.5rem 1rem rgba(0,0,0,.5); }
    .backup-card .card-header { background-color: rgba(255,255,255,0.08) !important; border-color: rgba(255,255,255,0.1) !important; }
    .backup-card .card-body { color: #e3e6f0 !important; }
    .backup-card .card-footer { background-color: rgba(255,255,255,0.05) !important; border-color: rgba(255,255,255,0.1) !important; color: #e3e6f0 !important; }
    .backup-card p, .backup-card strong, .backup-card li { color: #e3e6f0 !important; }
    .backup-card hr { border-color: rgba(255,255,255,0.1) !important; }
    .backup-card .border-left-info { border-left: 0.25rem solid #1cc88a !important; }
    .backup-card .border-left-warning { border-left: 0.25rem solid #f6c23e !important; }
    .category-check { cursor: pointer; }
    .category-check label { cursor: pointer; margin-bottom: 0; color: #e3e6f0 !important; }
    .category-check input { accent-color: #4e73df; }
    .spinner-overlay {
        position: fixed; top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.6); z-index: 9999;
        display: flex; align-items: center; justify-content: center;
    }
    .file-size { font-size: 0.8rem; color: #adb5bd !important; }
    .log-badge { font-size: 0.75rem; }
    .tab-content { padding-top: 1.5rem; }
    .restore-details td { font-size: 0.85rem; color: #e3e6f0 !important; }
    .restore-details th { background-color: rgba(255,255,255,0.1) !important; color: #e3e6f0 !important; border-color: rgba(255,255,255,0.1) !important; }
    .table-bordered { border-color: rgba(255,255,255,0.1) !important; }
    .table-bordered td, .table-bordered th { border-color: rgba(255,255,255,0.1) !important; color: #e3e6f0 !important; }
    .thead-light { background-color: rgba(255,255,255,0.08) !important; }
    .table-hover tbody tr:hover { background-color: rgba(255,255,255,0.08) !important; }
    .alert-info { background-color: rgba(23,162,184,0.15) !important; border-color: rgba(23,162,184,0.3) !important; color: #87ceeb !important; }
    .alert-danger { background-color: rgba(220,53,69,0.15) !important; border-color: rgba(220,53,69,0.3) !important; color: #ff7b7b !important; }
    .form-control { background-color: rgba(255,255,255,0.08) !important; border-color: rgba(255,255,255,0.1) !important; color: #e3e6f0 !important; }
    .form-control:focus { background-color: rgba(255,255,255,0.1) !important; border-color: #4e73df !important; color: #e3e6f0 !important; box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.3); }
    .custom-file-label { background-color: rgba(255,255,255,0.08) !important; border-color: rgba(255,255,255,0.1) !important; color: #adb5bd !important; }
    .custom-file-input ~ .custom-file-label::after { background-color: rgba(255,255,255,0.1) !important; color: #e3e6f0 !important; }
    select.form-control { color: #e3e6f0 !important; }
    select.form-control option { background-color: #2e3338; color: #e3e6f0; }
    .small { color: #adb5bd !important; }
    strong { color: #fff !important; }
</style>
@endsection

@section('content')
<div id="backupApp" class="container-fluid">

    <!-- Loading Overlay -->
    <div class="spinner-overlay" v-if="loading">
        <div class="text-center text-white">
            <div class="spinner-border mb-3" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            <h5>@{{ loadingMessage }}</h5>
        </div>
    </div>

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-light">
            <i class="fas fa-database"></i> Backup & Restore
        </h1>
    </div>

    <!-- Alert Messages -->
    <div v-if="alert.show" :class="'alert alert-' + alert.type + ' alert-dismissible fade show'" role="alert">
        <strong>@{{ alert.title }}</strong> @{{ alert.message }}
        <button type="button" class="close" @click="alert.show = false">
            <span>&times;</span>
        </button>
    </div>

    <!-- Tabs Navigation -->
    <ul class="nav nav-tabs mb-0" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#tabBackup" role="tab">
                <i class="fas fa-download"></i> Backup
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#tabRestore" role="tab">
                <i class="fas fa-upload"></i> Restore
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#tabFiles" role="tab">
                <i class="fas fa-folder-open"></i> Backup Files
                <span class="badge badge-primary ml-1">@{{ backupFiles.length }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#tabHistory" role="tab">
                <i class="fas fa-history"></i> History
            </a>
        </li>
    </ul>

    <div class="tab-content">

        <!-- ═══════════ BACKUP TAB ═══════════ -->
        <div class="tab-pane fade show active" id="tabBackup" role="tabpanel">
            <div class="row">
                <!-- Category Selection -->
                <div class="col-lg-8">
                    <div class="card shadow mb-4 backup-card">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-check-square"></i> Select Categories to Backup
                            </h6>
                       <div>
                            <button class="btn btn-sm btn-outline-primary mr-1"
                                style="color:white !important;" @click="selectAll">
                                Select All
                            </button>

                            <button class="btn btn-sm btn-outline-secondary"
                                style="color:white !important;" @click="deselectAll">
                                Clear
                            </button>
                        </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3" v-for="(label, key) in categories" :key="key">
                                    <div class="custom-control custom-checkbox category-check">
                                        <input type="checkbox"
                                               class="custom-control-input"
                                               :id="'cat_' + key"
                                               :value="key"
                                               v-model="selectedCategories">
                                        <label class="custom-control-label" :for="'cat_' + key">
                                            <i :class="getCategoryIcon(key)" class="mr-1"></i>
                                            @{{ label }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    @{{ selectedCategories.length }} of @{{ Object.keys(categories).length }} categories selected
                                </small>
                                <div>
                                    <button class="btn btn-primary mr-2"
                                            @click="backupSelected"
                                            :disabled="selectedCategories.length === 0 || loading">
                                        <i class="fas fa-download"></i> Backup Selected
                                    </button>
                                    <button class="btn btn-success"
                                            @click="backupAll"
                                            :disabled="loading">
                                        <i class="fas fa-database"></i> Backup All (Full)
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Info -->
                <div class="col-lg-4">
                    <div class="card shadow mb-4 backup-card border-left-info">
                        <div class="card-body">
                            <h6 class="font-weight-bold text-info mb-3">
                                <i class="fas fa-info-circle"></i> Backup Information
                            </h6>
                            <p class="small mb-2">
                                <strong>Format:</strong> JSON (preserves relationships)
                            </p>
                            <p class="small mb-2">
                                <strong>Storage:</strong> storage/app/backups/
                            </p>
                            <p class="small mb-2">
                                <strong>Includes:</strong> All related records & foreign key data
                            </p>
                            <hr>
                            <h6 class="font-weight-bold text-info mb-2">
                                <i class="fas fa-clock"></i> Scheduled Backups
                            </h6>
                            <p class="small mb-1"><i class="fas fa-calendar-day text-primary"></i> Daily at 2:00 AM</p>
                            <p class="small mb-1"><i class="fas fa-calendar-week text-success"></i> Weekly on Sundays 3:00 AM</p>
                            <p class="small mb-0"><i class="fas fa-calendar-alt text-warning"></i> Monthly on 1st at 4:00 AM</p>
                        </div>
                    </div>

                    <div class="card shadow mb-4 backup-card border-left-warning">
                        <div class="card-body">
                            <h6 class="font-weight-bold text-warning mb-2">
                                <i class="fas fa-exclamation-triangle"></i> Important Notes
                            </h6>
                            <ul class="small mb-0 pl-3">
                                <li>Shared tables (product_details, summaries) are scoped by category</li>
                                <li>Full backup includes all categories</li>
                                <li>Backup files can be downloaded for offsite storage</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ═══════════ RESTORE TAB ═══════════ -->
        <div class="tab-pane fade" id="tabRestore" role="tabpanel">
            <div class="row">
                <!-- Upload Restore -->
                <div class="col-lg-6">
                    <div class="card shadow mb-4 backup-card border-left-danger">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-danger">
                                <i class="fas fa-upload"></i> Restore from Upload
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label><strong>1. Select Backup File (JSON)</strong></label>
                                <div class="custom-file">
                                    <input type="file"
                                           class="custom-file-input"
                                           id="restoreFile"
                                           accept=".json"
                                           @change="onFileSelected">
                                    <label class="custom-file-label" for="restoreFile">
                                        @{{ restoreFileName || 'Choose backup file...' }}
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label><strong>2. Restore Type</strong></label>
                                <select class="form-control" v-model="restoreType">
                                    <option value="">-- Select restore type --</option>
                                    <option value="full">Full Restore (All Data)</option>
                                    <optgroup label="By Category">
                                        <option v-for="(label, key) in categories" :key="key" :value="key">
                                            @{{ label }}
                                        </option>
                                    </optgroup>
                                </select>
                            </div>

                            <!-- File Preview -->
                            <div v-if="restoreFileMeta" class="alert alert-info small">
                                <strong>File Info:</strong><br>
                                Type: @{{ restoreFileMeta.type }}<br>
                                Created: @{{ restoreFileMeta.created_at }}<br>
                                Tables: @{{ restoreFileMeta.tableCount }}
                            </div>

                            <div class="alert alert-danger small" v-if="restoreType">
                                <i class="fas fa-exclamation-triangle"></i>
                                <strong>Warning:</strong> Restoring will replace existing data for the selected category. This action cannot be undone. Make sure you have a current backup.
                            </div>

                            <button class="btn btn-danger btn-block"
                                    @click="restoreFromUpload"
                                    :disabled="!restoreFile || !restoreType || loading">
                                <i class="fas fa-undo"></i> Restore Data
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Restore from Server Files -->
                <div class="col-lg-6">
                    <div class="card shadow mb-4 backup-card border-left-warning">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-warning">
                                <i class="fas fa-server"></i> Restore from Server Backup
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label><strong>1. Select Server Backup</strong></label>
                                <select class="form-control" v-model="serverRestoreFile">
                                    <option value="">-- Select backup file --</option>
                                    <option v-for="file in backupFiles" :key="file.filename" :value="file.filename">
                                        @{{ file.filename }} (@{{ formatSize(file.size) }})
                                    </option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label><strong>2. Restore Type</strong></label>
                                <select class="form-control" v-model="serverRestoreType">
                                    <option value="">-- Select restore type --</option>
                                    <option value="full">Full Restore (All Data)</option>
                                    <optgroup label="By Category">
                                        <option v-for="(label, key) in categories" :key="key" :value="key">
                                            @{{ label }}
                                        </option>
                                    </optgroup>
                                </select>
                            </div>

                            <div class="alert alert-danger small" v-if="serverRestoreType">
                                <i class="fas fa-exclamation-triangle"></i>
                                <strong>Warning:</strong> Restoring will replace existing data for the selected category. This action cannot be undone.
                            </div>

                            <button class="btn btn-warning btn-block"
                                    @click="restoreFromServer"
                                    :disabled="!serverRestoreFile || !serverRestoreType || loading">
                                <i class="fas fa-undo"></i> Restore from Server
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Restore Results -->
            <div class="card shadow mb-4" v-if="restoreResult">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-check-circle"></i> Restore Results
                    </h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-bordered restore-details">
                        <thead class="thead-light">
                            <tr>
                                <th>Table</th>
                                <th>Records Restored</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(count, table) in restoreResult" :key="table">
                                <td><code>@{{ table }}</code></td>
                                <td><span class="badge badge-success">@{{ count }}</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ═══════════ FILES TAB ═══════════ -->
        <div class="tab-pane fade" id="tabFiles" role="tabpanel">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-folder-open"></i> Stored Backup Files
                    </h6>
                    <button class="btn btn-sm btn-outline-primary" @click="refreshFiles">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                </div>
                <div class="card-body">
                    <div v-if="backupFiles.length === 0" class="text-center py-4 text-muted">
                        <i class="fas fa-inbox fa-3x mb-3"></i>
                        <p>No backup files found.</p>
                    </div>
                    <div class="table-responsive" v-else>
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Filename</th>
                                    <th>Size</th>
                                    <th>Date</th>
                                    <th width="200">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(file, index) in backupFiles" :key="file.filename">
                                    <td>@{{ index + 1 }}</td>
                                    <td>
                                        <i class="fas fa-file-code text-primary"></i>
                                        @{{ file.filename }}
                                    </td>
                                    <td class="file-size">@{{ formatSize(file.size) }}</td>
                                    <td>@{{ file.last_modified }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-info mr-1" @click="downloadFile(file.filename)" title="Download">
                                            <i class="fas fa-download"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" @click="deleteFile(file.filename)" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- ═══════════ HISTORY TAB ═══════════ -->
        <div class="tab-pane fade" id="tabHistory" role="tabpanel">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-history"></i> Backup & Restore History
                    </h6>
                    <button class="btn btn-sm btn-outline-primary" @click="refreshHistory">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                </div>
                <div class="card-body">
                    <div v-if="history.length === 0" class="text-center py-4 text-muted">
                        <i class="fas fa-history fa-3x mb-3"></i>
                        <p>No backup history yet.</p>
                    </div>
                    <div class="table-responsive" v-else>
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Type</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Message</th>
                                    <th>User</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(log, index) in history" :key="log.id">
                                    <td>@{{ index + 1 }}</td>
                                    <td>
                                        <span :class="log.type === 'backup' ? 'badge badge-primary' : 'badge badge-warning'" class="log-badge">
                                            <i :class="log.type === 'backup' ? 'fas fa-download' : 'fas fa-upload'"></i>
                                            @{{ log.type }}
                                        </span>
                                    </td>
                                    <td>@{{ log.category }}</td>
                                    <td>
                                        <span :class="log.status === 'success' ? 'badge badge-success' : 'badge badge-danger'" class="log-badge">
                                            @{{ log.status }}
                                        </span>
                                    </td>
                                    <td class="small">@{{ log.message || '-' }}</td>
                                    <td>@{{ log.user ? log.user.name : 'System' }}</td>
                                    <td class="small">@{{ log.created_at }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div><!-- /tab-content -->
</div>
@endsection

@section('scripts')
<script>
const backupApp = new Vue({
    el: '#backupApp',
    data() {
        return {
            // Backup
            categories: @json($categories),
            selectedCategories: [],

            // Files & History
            backupFiles: @json($backupFiles),
            history: @json($history),

            // Restore (Upload)
            restoreFile: null,
            restoreFileName: '',
            restoreFileMeta: null,
            restoreType: '',

            // Restore (Server)
            serverRestoreFile: '',
            serverRestoreType: '',

            // Restore Result
            restoreResult: null,

            // UI State
            loading: false,
            loadingMessage: 'Processing...',
            alert: { show: false, type: 'success', title: '', message: '' },
        };
    },
    methods: {
        // ── Category Selection ─────────────
        selectAll() {
            this.selectedCategories = Object.keys(this.categories);
        },
        deselectAll() {
            this.selectedCategories = [];
        },
        getCategoryIcon(key) {
            const icons = {
                purchase_orders: 'fas fa-shopping-cart text-info',
                sales_orders: 'fas fa-file-invoice-dollar text-success',
                products_categories: 'fas fa-boxes text-primary',
                vendors: 'fas fa-truck text-warning',
                customers: 'fas fa-users text-info',
                expenses: 'fas fa-money-bill-wave text-danger',
                job_orders: 'fas fa-tools text-secondary',
                product_returns: 'fas fa-undo-alt text-warning',
                system_settings: 'fas fa-cogs text-dark',
            };
            return icons[key] || 'fas fa-table';
        },

        // ── Backup Operations ──────────────
        backupSelected() {
            if (this.selectedCategories.length === 0) return;
            this.loading = true;
            this.loadingMessage = 'Creating backup for selected categories...';

            $.ajax({
                url: '{{ route("backup.export.categories") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    categories: this.selectedCategories,
                },
                success: (res) => {
                    this.showAlert('success', 'Success!', res.message + ' File: ' + res.filename);
                    this.refreshFiles();
                    this.refreshHistory();
                },
                error: (xhr) => {
                    const msg = xhr.responseJSON ? xhr.responseJSON.message : 'An error occurred.';
                    this.showAlert('danger', 'Error!', msg);
                },
                complete: () => { this.loading = false; }
            });
        },

        backupAll() {
            Swal.fire({
                title: 'Full Database Backup',
                text: 'This will create a complete backup of all data. Continue?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#1cc88a',
                confirmButtonText: 'Yes, backup all!'
            }).then((result) => {
                if (!result.value) return;
                this.loading = true;
                this.loadingMessage = 'Creating full database backup...';

                $.ajax({
                    url: '{{ route("backup.export.all") }}',
                    method: 'POST',
                    data: { _token: '{{ csrf_token() }}' },
                    success: (res) => {
                        this.showAlert('success', 'Success!', res.message + ' File: ' + res.filename);
                        this.refreshFiles();
                        this.refreshHistory();
                    },
                    error: (xhr) => {
                        const msg = xhr.responseJSON ? xhr.responseJSON.message : 'An error occurred.';
                        this.showAlert('danger', 'Error!', msg);
                    },
                    complete: () => { this.loading = false; }
                });
            });
        },

        // ── Restore Operations ─────────────
        onFileSelected(event) {
            const file = event.target.files[0];
            if (!file) {
                this.restoreFile = null;
                this.restoreFileName = '';
                this.restoreFileMeta = null;
                return;
            }
            this.restoreFile = file;
            this.restoreFileName = file.name;

            // Parse file to show meta preview
            const reader = new FileReader();
            reader.onload = (e) => {
                try {
                    const data = JSON.parse(e.target.result);
                    if (data.meta) {
                        this.restoreFileMeta = {
                            type: data.meta.type || 'unknown',
                            created_at: data.meta.created_at || 'unknown',
                            tableCount: data.tables ? Object.keys(data.tables).length : 0,
                        };
                    }
                } catch (err) {
                    this.restoreFileMeta = null;
                }
            };
            reader.readAsText(file);
        },

        restoreFromUpload() {
            if (!this.restoreFile || !this.restoreType) return;

            Swal.fire({
                title: 'Confirm Restore',
                html: '<strong class="text-danger">This will overwrite existing data for: ' + this.restoreType + '</strong><br>Are you sure you want to continue?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e74a3b',
                confirmButtonText: 'Yes, restore!'
            }).then((result) => {
                if (!result.value) return;

                this.loading = true;
                this.loadingMessage = 'Restoring data...';
                this.restoreResult = null;

                const formData = new FormData();
                formData.append('backup_file', this.restoreFile);
                formData.append('restore_type', this.restoreType);
                formData.append('_token', '{{ csrf_token() }}');

                $.ajax({
                    url: '{{ route("restore.upload") }}',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: (res) => {
                        this.showAlert('success', 'Restored!', res.message);
                        this.restoreResult = res.details;
                        this.refreshHistory();
                    },
                    error: (xhr) => {
                        const msg = xhr.responseJSON ? xhr.responseJSON.message : 'Restore failed.';
                        this.showAlert('danger', 'Error!', msg);
                    },
                    complete: () => { this.loading = false; }
                });
            });
        },

        restoreFromServer() {
            if (!this.serverRestoreFile || !this.serverRestoreType) return;

            Swal.fire({
                title: 'Confirm Restore from Server',
                html: '<strong class="text-danger">Restoring: ' + this.serverRestoreType + '</strong><br>From: ' + this.serverRestoreFile + '<br><br>This will overwrite existing data. Continue?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e74a3b',
                confirmButtonText: 'Yes, restore!'
            }).then((result) => {
                if (!result.value) return;

                this.loading = true;
                this.loadingMessage = 'Restoring data from server backup...';
                this.restoreResult = null;

                $.ajax({
                    url: '{{ route("restore.from-file") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        filename: this.serverRestoreFile,
                        restore_type: this.serverRestoreType,
                    },
                    success: (res) => {
                        this.showAlert('success', 'Restored!', res.message);
                        this.restoreResult = res.details;
                        this.refreshHistory();
                    },
                    error: (xhr) => {
                        const msg = xhr.responseJSON ? xhr.responseJSON.message : 'Restore failed.';
                        this.showAlert('danger', 'Error!', msg);
                    },
                    complete: () => { this.loading = false; }
                });
            });
        },

        // ── File Operations ────────────────
        downloadFile(filename) {
            // Submit via hidden form for file download
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("backup.download") }}';
            form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}">' +
                             '<input type="hidden" name="filename" value="' + filename + '">';
            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
        },

        deleteFile(filename) {
            Swal.fire({
                title: 'Delete Backup?',
                text: 'Delete ' + filename + '? This cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e74a3b',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (!result.value) return;

                $.ajax({
                    url: '{{ route("backup.delete") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        filename: filename,
                    },
                    success: (res) => {
                        this.showAlert('success', 'Deleted!', res.message);
                        this.refreshFiles();
                    },
                    error: (xhr) => {
                        const msg = xhr.responseJSON ? xhr.responseJSON.message : 'Delete failed.';
                        this.showAlert('danger', 'Error!', msg);
                    }
                });
            });
        },

        // ── Refresh Data ───────────────────
        refreshFiles() {
            $.get('{{ route("backup.files") }}', (res) => {
                if (res.success) this.backupFiles = res.files;
            });
        },

        refreshHistory() {
            $.get('{{ route("backup.history") }}', (res) => {
                if (res.success) this.history = res.history;
            });
        },

        // ── Helpers ────────────────────────
        formatSize(bytes) {
            if (!bytes) return '0 B';
            const k = 1024;
            const sizes = ['B', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        },

        showAlert(type, title, message) {
            this.alert = { show: true, type, title, message };
            setTimeout(() => { this.alert.show = false; }, 8000);
        },
    },
    mounted() {
        // Refresh on tab activate
        $('a[data-toggle="tab"]').on('shown.bs.tab', (e) => {
            const target = $(e.target).attr('href');
            if (target === '#tabFiles') this.refreshFiles();
            if (target === '#tabHistory') this.refreshHistory();
        });
    }
});
</script>
@endsection
