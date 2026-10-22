@extends('admin.partials.master')
@section('admin_content')
    <section id="dashboard-ecommerce">
        <div class="row">
            <div class="col-sm-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header pb-1 bg-primary text-white" style="border-radius: 6px 6px 0 0;">
                        <h4 class="card-title text-white mb-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div><i class="bx bx-bell"></i> Sent User Notifications</div>
                                <div>
                                    <a href="{{route('admin.notice.create')}}" class="btn btn-light btn-sm text-primary font-weight-bold">
                                        <i class="bx bx-send"></i> Send New Notification
                                    </a>
                                </div>
                            </div>
                        </h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard">
                            <div class="alert alert-light border mb-2 py-1 text-muted d-flex align-items-center">
                                <i class="bx bx-info-circle text-primary font-medium-3 mr-75"></i>
                                <div>
                                    <strong>Realtime In-App Notifications:</strong> Active notices are pushed live to users within <strong>15 seconds</strong>. You can send to <strong>All Users (Broadcast)</strong> or to a <strong>Specific User</strong>!
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover table-striped">
                                    <thead class="thead-light">
                                    <tr>
                                        <th style="width: 40px;">#</th>
                                        <th>Target / Recipient</th>
                                        <th>Type</th>
                                        <th>Title</th>
                                        <th>Message</th>
                                        <th>Status</th>
                                        <th>Sent Time</th>
                                        <th style="width: 140px;">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($datas as $key => $row)
                                        <tr>
                                            <td>{{$key + 1}}</td>
                                            <td>
                                                @if($row->user_id)
                                                    <span class="badge badge-light-primary px-1 py-50 font-small-2">
                                                        <i class="bx bx-user"></i> {{$row->user ? ($row->user->name ?: $row->user->phone) : 'User #' . $row->user_id}}
                                                        @if($row->user && $row->user->phone)
                                                            <br><small class="text-muted font-weight-bold">{{$row->user->phone}}</small>
                                                        @endif
                                                    </span>
                                                @else
                                                    <span class="badge badge-light-success px-1 py-50 font-small-2">
                                                        <i class="bx bx-broadcast"></i> All Users (Broadcast)
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($row->notice_type == 'broadcast')
                                                    <span class="badge badge-primary px-1 py-50"><i class="bx bx-broadcast"></i> Broadcast</span>
                                                @elseif($row->notice_type == 'popup')
                                                    <span class="badge badge-warning px-1 py-50"><i class="bx bx-bell"></i> Pop-up</span>
                                                @elseif($row->notice_type == 'marquee')
                                                    <span class="badge badge-info px-1 py-50"><i class="bx bx-refresh"></i> Marquee</span>
                                                @elseif($row->notice_type == 'reward')
                                                    <span class="badge badge-success px-1 py-50"><i class="bx bx-gift"></i> Bonus / Reward</span>
                                                @else
                                                    <span class="badge badge-secondary px-1 py-50">{{ucfirst($row->notice_type)}}</span>
                                                @endif
                                            </td>
                                            <td><strong class="text-dark">{{$row->notice_title}}</strong></td>
                                            <td>
                                                <div style="max-width: 320px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{$row->notice_desc}}">
                                                    {{$row->notice_desc}}
                                                </div>
                                            </td>
                                            <td>
                                                <a href="{{route('admin.notice.toggle', $row->id)}}" class="btn btn-sm py-25 px-50 @if($row->status == 'active') btn-success @else btn-secondary @endif" title="Click to toggle status">
                                                    @if($row->status == 'active')
                                                        <i class="bx bx-check"></i> Live (Active)
                                                    @else
                                                        <i class="bx bx-x"></i> Hidden (Inactive)
                                                    @endif
                                                </a>
                                            </td>
                                            <td><small class="text-muted">{{$row->created_at ? \Carbon\Carbon::parse($row->created_at)->diffForHumans() : '-'}}</small></td>
                                            <td>
                                                <a href="{{route('admin.notice.create', $row->id)}}"
                                                   class="btn btn-sm btn-warning" data-toggle="tooltip" title='Edit'>
                                                    <i class="bx bx-pencil"></i>
                                                </a>
                                                <form method="POST" action="{{route('admin.notice.delete', $row->id)}}"
                                                      class="d-inline" onsubmit="return confirm('Are you sure you want to delete this notification?')">
                                                    @csrf
                                                    {{method_field('DELETE')}}
                                                    <button type="submit" class="btn btn-sm btn-danger" data-toggle="tooltip" title='Delete'>
                                                        <i class="bx bx-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-4 text-muted">
                                                <i class="bx bx-bell-off" style="font-size: 38px;"></i>
                                                <p class="mb-50 mt-1 font-weight-bold">No notifications sent yet.</p>
                                                <a href="{{route('admin.notice.create')}}" class="btn btn-primary btn-sm">
                                                    <i class="bx bx-send"></i> Send Your First Notification
                                                </a>
                                            </td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
