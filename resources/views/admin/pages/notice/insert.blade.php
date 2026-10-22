@extends('admin.partials.master')
@section('admin_content')
    <section id="dashboard-ecommerce">
        <div class="row">
            <div class="col-12">
                <form action="{{route('admin.notice.insert')}}" method="POST" enctype="multipart/form-data">@csrf
                    <input type="hidden" name="id" value="{{$data ? $data->id : ''}}">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-primary text-white" style="border-radius: 6px 6px 0 0;">
                            <h4 class="card-title text-white mb-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div><i class="bx bx-broadcast"></i> {{$data ? 'Update' : 'Publish New'}} Broadcast Notice</div>
                                    <div>
                                        <a href="{{route('admin.notice.index')}}" class="btn btn-light btn-sm text-primary font-weight-bold">
                                            <i class="bx bx-arrow-back"></i> Notice List
                                        </a>
                                    </div>
                                </div>
                            </h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <div class="alert alert-info py-1 mb-2">
                                    <i class="bx bx-info-circle"></i> <strong>Real-time Broadcast:</strong> Active notices appear immediately on the user panel as a live pop-up modal and rolling announcement ticker for all users!
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <label for="notice_type" class="font-weight-bold">Notice Type</label>
                                        <select name="notice_type" id="notice_type" class="form-control" required>
                                            <option value="broadcast" @if(($data->notice_type ?? '') == 'broadcast') selected @endif>📢 Real-time Broadcast (Pop-up + Ticker)</option>
                                            <option value="popup" @if(($data->notice_type ?? '') == 'popup') selected @endif>⚠️ Urgent Pop-up Alert</option>
                                            <option value="marquee" @if(($data->notice_type ?? '') == 'marquee') selected @endif>🔄 Rolling Marquee Ticker Only</option>
                                            <option value="information" @if(($data->notice_type ?? '') == 'information') selected @endif>ℹ️ General Announcement</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label for="status" class="font-weight-bold">Status</label>
                                        <select name="status" id="status" class="form-control">
                                            <option value="active" @if(($data->status ?? 'active') == 'active') selected @endif>✅ Active (Show to Users)</option>
                                            <option value="inactive" @if(($data->status ?? '') == 'inactive') selected @endif>❌ Inactive (Draft / Hidden)</option>
                                        </select>
                                    </div>

                                    <div class="col-12 mb-2">
                                        <label for="notice_title" class="font-weight-bold">Broadcast Title</label>
                                        <input type="text" class="form-control"
                                               name="notice_title" id="notice_title"
                                               placeholder="e.g. 🎉 Special Bonus / Important Update for All Users"
                                               value="{{$data ? $data->notice_title : old('notice_title')}}" required>
                                    </div>

                                    <div class="col-12 mb-2">
                                        <label for="notice_desc" class="font-weight-bold">Broadcast Message / Description</label>
                                        <textarea class="form-control" name="notice_desc" id="notice_desc" rows="5"
                                                  placeholder="Type the full message here. It will be displayed in real time to all users..." required>{{$data ? $data->notice_desc : old('notice_desc')}}</textarea>
                                    </div>
                                </div>

                                <div class="mt-2 text-right">
                                    <a href="{{route('admin.notice.index')}}" class="btn btn-secondary mr-1">Cancel</a>
                                    <button type="submit" class="btn btn-success px-3">
                                        <i class="bx bx-send"></i> {{$data ? 'Update Broadcast' : 'Publish Broadcast Now'}}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
