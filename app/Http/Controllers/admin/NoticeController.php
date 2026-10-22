<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use App\Models\User;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
    public $route = 'admin.notice';

    public function index()
    {
        $datas = Notice::with('user:id,name,phone,ref_id')->orderByDesc('id')->get();
        return view('admin.pages.notice.index', compact('datas'));
    }

    public function view($id)
    {
        $data = Notice::with('user')->find($id);
        return view('admin.pages.notice.view', compact('data'));
    }

    public function create(Request $request, $id = null)
    {
        $data = null;
        $selectedUser = null;

        if ($id) {
            $data = Notice::with('user')->find($id);
            if ($data && $data->user) {
                $selectedUser = $data->user;
            }
        } elseif ($request->filled('user_id')) {
            $selectedUser = User::find($request->user_id);
        }

        return view('admin.pages.notice.insert', compact('data', 'selectedUser'));
    }

    public function insert_or_update(Request $request)
    {
        $this->validate($request, [
            'notice_type'  => 'required',
            'notice_title' => 'required',
            'notice_desc'  => 'required',
        ]);

        $userId = null;
        if ($request->target_type === 'specific') {
            $identifier = trim($request->user_identifier ?? '');
            if (!empty($identifier)) {
                $user = User::where('phone', $identifier)
                    ->orWhere('id', $identifier)
                    ->orWhere('ref_id', $identifier)
                    ->first();
                if (!$user) {
                    return back()->withInput()->with('error', "User not found with phone/ID: '{$identifier}'");
                }
                $userId = $user->id;
            } else {
                return back()->withInput()->with('error', 'Please enter a User Phone or User ID for specific user notification.');
            }
        }

        if ($request->id) {
            $model = Notice::findOrFail($request->id);
            $model->status = $request->status ?? 'active';
        } else {
            $model = new Notice();
            $model->status = $request->status ?? 'active';
        }

        $model->user_id      = $userId;
        $model->notice_type  = $request->notice_type;
        $model->notice_title = $request->notice_title;
        $model->notice_desc  = $request->notice_desc;

        $model->save();

        $successMsg = $userId 
            ? "Notification sent successfully to User ({$request->user_identifier})!"
            : "Broadcast notification published successfully to All Users!";

        return redirect()->route($this->route . '.index')->with('success', $successMsg);
    }

    public function delete($id)
    {
        $model = Notice::find($id);
        if ($model) {
            $model->delete();
        }
        return redirect()->route($this->route . '.index')->with('success', 'Notification Deleted Successfully.');
    }

    public function toggle_status($id)
    {
        $model = Notice::findOrFail($id);
        $model->status = $model->status === 'active' ? 'inactive' : 'active';
        $model->save();
        return redirect()->route($this->route . '.index')->with('success', 'Status changed to ' . $model->status);
    }
}
