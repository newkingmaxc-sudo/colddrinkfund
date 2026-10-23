@extends('admin.partials.master')
@section('admin_content')
<section id="app-update-dashboard">
    <div class="row">
        <!-- Live App Overview Cards -->
        <div class="col-xl-3 col-md-6 col-12">
            <div class="card text-center" style="border-left: 4px solid #00cfe8;">
                <div class="card-body">
                    <div class="badge-circle badge-circle-lg badge-circle-light-info mx-auto mb-50">
                        <i class="bx bx-mobile-alt font-medium-5"></i>
                    </div>
                    <div class="text-muted font-small-3">Active Version</div>
                    <h3 class="font-weight-bolder mb-0 mt-50">v{{ $latest ? $latest->version_name : '1.1.0' }}</h3>
                    <small class="text-primary font-weight-bold">Build Code: {{ $latest ? $latest->version_code : 101 }}</small>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 col-12">
            <div class="card text-center" style="border-left: 4px solid #39da8a;">
                <div class="card-body">
                    <div class="badge-circle badge-circle-lg badge-circle-light-success mx-auto mb-50">
                        <i class="bx bx-download font-medium-5"></i>
                    </div>
                    <div class="text-muted font-small-3">APK Downloads</div>
                    <h3 class="font-weight-bolder mb-0 mt-50">{{ $latest ? number_format($latest->download_count) : 0 }}</h3>
                    <small class="text-success font-weight-bold">Tracked via App</small>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 col-12">
            <div class="card text-center" style="border-left: 4px solid {{ ($latest && $latest->is_forced) ? '#ff5b5c' : '#fdac41' }};">
                <div class="card-body">
                    <div class="badge-circle badge-circle-lg badge-circle-light-{{ ($latest && $latest->is_forced) ? 'danger' : 'warning' }} mx-auto mb-50">
                        <i class="bx {{ ($latest && $latest->is_forced) ? 'bx-lock-alt' : 'bx-bell' }} font-medium-5"></i>
                    </div>
                    <div class="text-muted font-small-3">Update Enforcement</div>
                    <h4 class="font-weight-bolder mb-0 mt-50">
                        @if($latest && $latest->is_forced)
                            <span class="text-danger">Mandatory (Forced)</span>
                        @else
                            <span class="text-warning">Optional (Dismissible)</span>
                        @endif
                    </h4>
                    <small class="text-muted">In-App Modal Type</small>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 col-12">
            <div class="card text-center" style="border-left: 4px solid #5a8dee;">
                <div class="card-body">
                    <div class="badge-circle badge-circle-lg badge-circle-light-primary mx-auto mb-50">
                        <i class="bx bx-file font-medium-5"></i>
                    </div>
                    <div class="text-muted font-small-3">APK File on Server</div>
                    <h4 class="font-weight-bolder mb-0 mt-50">
                        @if($apkExists)
                            <span class="text-success">Available ({{ $apkSize }})</span>
                        @else
                            <span class="text-muted">Default / plg.apk</span>
                        @endif
                    </h4>
                    <a href="{{ route('download.apk') }}" target="_blank" class="badge badge-primary mt-50">
                        <i class="bx bx-download"></i> Test Download
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Publish New Version Form & Guide -->
    <div class="row">
        <div class="col-lg-7 col-12">
            <div class="card">
                <div class="card-header border-bottom">
                    <h4 class="card-title font-weight-bold">
                        <i class="bx bx-cloud-upload text-primary mr-50"></i> Release New App Version & Update
                    </h4>
                    <p class="text-muted mb-0 font-small-3">
                        When you release a new version here, all user phones running the Cold Drink Fund APK will automatically get the update notification popup.
                    </p>
                </div>
                <div class="card-body pt-2">
                    <form action="{{ route('admin.app_version.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 col-12 mb-1">
                                <label class="font-weight-bold">Version Code (Numeric Build Number) <span class="text-danger">*</span></label>
                                <input type="number" name="version_code" class="form-control" value="{{ ($latest ? $latest->version_code : 101) + 1 }}" required min="1">
                                <small class="text-muted">Must be greater than current (Current: {{ $latest ? $latest->version_code : 101 }})</small>
                            </div>

                            <div class="col-md-6 col-12 mb-1">
                                <label class="font-weight-bold">Version Name <span class="text-danger">*</span></label>
                                <input type="text" name="version_name" class="form-control" placeholder="e.g. 1.2.0" value="1.2.0" required>
                                <small class="text-muted">Visible version label shown to users</small>
                            </div>

                            <div class="col-12 mb-1">
                                <label class="font-weight-bold">Update Notification Title</label>
                                <input type="text" name="title" class="form-control" value="🚀 New Update Available! / نیا ورژن دستیاب ہے" placeholder="Notification Title">
                            </div>

                            <div class="col-12 mb-1">
                                <label class="font-weight-bold">What's New / Changelog (1 item per line)</label>
                                <textarea name="changelog" rows="4" class="form-control" placeholder="🥤 Cold Drink Bottling Plant (Tap to Collect)&#10;🍾 Lucky Bottle Cap Daily Rewards&#10;🎡 Lucky Spin Wheel with Instant Cash&#10;⚡ Super-Fast Deposit & Instant Withdrawal Processing&#10;🛡️ Enhanced Security & Bug Fixes">{{ $latest ? $latest->changelog : '' }}</textarea>
                                <small class="text-muted">Users will see this bulleted list inside the app update modal.</small>
                            </div>

                            <div class="col-md-6 col-12 mb-1">
                                <label class="font-weight-bold">Upload New APK File (.apk)</label>
                                <div class="custom-file">
                                    <input type="file" name="apk_file" class="custom-file-input" id="apkFile" accept=".apk">
                                    <label class="custom-file-label" for="apkFile">Choose .apk file (Max 100MB)</label>
                                </div>
                                <small class="text-muted">Saves as official ColdDrinkFund.apk</small>
                            </div>

                            <div class="col-md-6 col-12 mb-1">
                                <label class="font-weight-bold">Or External APK Download URL</label>
                                <input type="url" name="apk_url" class="form-control" placeholder="https://github.com/.../app-release.apk" value="{{ $latest ? $latest->apk_url : '/download-apk' }}">
                                <small class="text-muted">Optional: GitHub release or CDN direct link</small>
                            </div>

                            <div class="col-md-6 col-12 mb-1">
                                <label class="font-weight-bold">Mandatory Force Update?</label>
                                <select name="is_forced" class="form-control">
                                    <option value="0" {{ ($latest && !$latest->is_forced) ? 'selected' : '' }}>No - Optional (User can skip or update later)</option>
                                    <option value="1" {{ ($latest && $latest->is_forced) ? 'selected' : '' }}>Yes - Mandatory (User must update to continue)</option>
                                </select>
                            </div>

                            <div class="col-md-6 col-12 mb-1">
                                <label class="font-weight-bold">Min Supported Version Code</label>
                                <input type="number" name="min_supported_version" class="form-control" value="{{ $latest ? $latest->min_supported_version : 100 }}">
                                <small class="text-muted">Older than this code will be forced anyway</small>
                            </div>

                            <div class="col-12 mt-1">
                                <button type="submit" class="btn btn-primary btn-block font-weight-bold py-1 shadow-sm">
                                    <i class="bx bx-check-circle mr-50"></i> Publish App Update Now
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Android APK Standalone Guide -->
        <div class="col-lg-5 col-12">
            <div class="card bg-light-primary border-primary">
                <div class="card-body">
                    <h5 class="card-title text-primary font-weight-bold mb-1">
                        <i class="bx bxl-android mr-50"></i> Standalone Android Native APK
                    </h5>
                    <p class="font-small-3 text-dark">
                        Cold Drink Fund is now fully equipped with a <strong>Standalone Native Android Package</strong> located in the repository under <code>/android</code>.
                    </p>

                    <div class="list-group list-group-flush mb-2 font-small-3">
                        <div class="list-group-item bg-transparent px-0 py-50 d-flex justify-content-between align-items-center">
                            <span><i class="bx bx-check text-success mr-50"></i> App Name</span>
                            <strong>Cold Drink Fund</strong>
                        </div>
                        <div class="list-group-item bg-transparent px-0 py-50 d-flex justify-content-between align-items-center">
                            <span><i class="bx bx-check text-success mr-50"></i> Package ID</span>
                            <code>com.colddrinkfund.app</code>
                        </div>
                        <div class="list-group-item bg-transparent px-0 py-50 d-flex justify-content-between align-items-center">
                            <span><i class="bx bx-check text-success mr-50"></i> Native Camera & File Picker</span>
                            <span class="badge badge-success">Enabled</span>
                        </div>
                        <div class="list-group-item bg-transparent px-0 py-50 d-flex justify-content-between align-items-center">
                            <span><i class="bx bx-check text-success mr-50"></i> In-App Auto-Update Bridge</span>
                            <span class="badge badge-success">Active</span>
                        </div>
                        <div class="list-group-item bg-transparent px-0 py-50 d-flex justify-content-between align-items-center">
                            <span><i class="bx bx-check text-success mr-50"></i> GitHub Actions Automated Cloud Build</span>
                            <span class="badge badge-info">Configured</span>
                        </div>
                    </div>

                    <div class="alert alert-primary mb-0 font-small-3" role="alert">
                        <strong><i class="bx bx-info-circle mr-50"></i> Auto-Update Mechanism:</strong><br>
                        1. The APK queries <code>/api/app/version</code> automatically upon launch.<br>
                        2. If a newer <code>version_code</code> exists, a styled in-app modal opens.<br>
                        3. Tapping <em>"Update Now"</em> downloads the latest APK and launches the Android Package Installer directly on the user's phone!
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Release History Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom">
                    <h4 class="card-title font-weight-bold">
                        <i class="bx bx-history text-primary mr-50"></i> App Version Release History
                    </h4>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Version</th>
                                <th>Build Code</th>
                                <th>Release Title</th>
                                <th>Type</th>
                                <th>Downloads</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($versions as $ver)
                            <tr>
                                <td>
                                    <span class="badge badge-light-primary font-weight-bold font-medium-1">v{{ $ver->version_name }}</span>
                                </td>
                                <td><code>{{ $ver->version_code }}</code></td>
                                <td>
                                    <strong>{{ $ver->title }}</strong>
                                    @if($ver->changelog)
                                        <div class="text-muted font-small-2 mt-25" style="white-space: pre-line; max-height: 60px; overflow-y: auto;">{{ Str::limit($ver->changelog, 100) }}</div>
                                    @endif
                                </td>
                                <td>
                                    @if($ver->is_forced)
                                        <span class="badge badge-danger">Mandatory</span>
                                    @else
                                        <span class="badge badge-light-warning">Optional</span>
                                    @endif
                                </td>
                                <td>
                                    <i class="bx bx-download text-muted mr-25"></i> {{ number_format($ver->download_count) }}
                                </td>
                                <td>{{ $ver->created_at->format('d M, Y H:i') }}</td>
                                <td>
                                    @if($ver->is_active)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-secondary">Disabled</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.app_version.toggle', $ver->id) }}" class="btn btn-sm btn-outline-{{ $ver->is_active ? 'warning' : 'success' }}">
                                        {{ $ver->is_active ? 'Disable' : 'Enable' }}
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-2 text-muted">No versions recorded yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
