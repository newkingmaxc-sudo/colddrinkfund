<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use App\Models\Deposit;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentMethodController extends Controller
{
    public $route = 'admin.method';

    public function index()
    {
        $today = now()->startOfDay();
        $methods = PaymentMethod::all()->map(function($m) use ($today) {
            $m->today_received = (float)Deposit::where('status', 'approved')
                ->where(function($q) use ($m) {
                    $q->where('method_name', $m->name)
                      ->orWhere('method_name', 'like', '%' . $m->name . '%');
                })
                ->where('created_at', '>=', $today)
                ->sum('amount');
            return $m;
        });

        return view('admin.pages.method.index', compact('methods'));
    }

    public function create($id=null)
    {
        $data = null;
        if ($id){
            $data = PaymentMethod::find($id);
        }
        return view('admin.pages.method.insert', compact('data'));
    }

    public function insert_or_update(Request $request)
    {
        $this->validate($request, [
            'name'    => 'required|string|max:100',
            'address' => 'required|string|max:255',
        ]);

        if ($request->id) {
            $model = PaymentMethod::findOrFail($request->id);
        } else {
            $model = new PaymentMethod();
        }

        // 1. Handle Photo Upload safely for local & Vercel serverless environments
        if ($request->hasFile('photo')) {
            try {
                $file = $request->file('photo');
                $destDir = public_path('upload/setting');
                if (@is_writable($destDir) || @is_writable(public_path())) {
                    if (!is_dir($destDir)) {
                        @mkdir($destDir, 0755, true);
                    }
                    $ext = $file->getClientOriginalExtension();
                    $filename = time() . '_' . Str::random(6) . '.' . $ext;
                    $file->move($destDir, $filename);
                    $model->photo = '/upload/setting/' . $filename;
                } else {
                    // Read-only filesystem / Vercel: Store as base64 data URI (100% persistent)
                    $model->photo = 'data:' . $file->getMimeType() . ';base64,' . base64_encode(file_get_contents($file->getRealPath()));
                }
            } catch (\Throwable $e) {
                if ($request->hasFile('photo')) {
                    $file = $request->file('photo');
                    $model->photo = 'data:' . $file->getMimeType() . ';base64,' . base64_encode(file_get_contents($file->getRealPath()));
                }
            }
        } elseif ($request->filled('preset_icon')) {
            $model->photo = $request->preset_icon;
        } elseif (empty($model->photo)) {
            $model->photo = '/img/icon.png';
        }

        // 2. Handle Custom QR Code Upload safely for local & Vercel serverless environments
        if ($request->hasFile('qr_code')) {
            try {
                $file = $request->file('qr_code');
                $destDir = public_path('upload/qr');
                if (@is_writable($destDir) || @is_writable(public_path())) {
                    if (!is_dir($destDir)) {
                        @mkdir($destDir, 0755, true);
                    }
                    $ext = $file->getClientOriginalExtension();
                    $filename = 'qr_' . time() . '_' . Str::random(6) . '.' . $ext;
                    $file->move($destDir, $filename);
                    $model->qr_code = '/upload/qr/' . $filename;
                } else {
                    $model->qr_code = 'data:' . $file->getMimeType() . ';base64,' . base64_encode(file_get_contents($file->getRealPath()));
                }
            } catch (\Throwable $e) {
                if ($request->hasFile('qr_code')) {
                    $file = $request->file('qr_code');
                    $model->qr_code = 'data:' . $file->getMimeType() . ';base64,' . base64_encode(file_get_contents($file->getRealPath()));
                }
            }
        }

        $model->name = trim($request->name);
        $model->address = trim($request->address);
        $model->account_title = trim($request->account_title ?: $request->name);
        $model->account_type = $request->account_type ?: 'mobile_wallet';
        $model->daily_limit = (float)($request->daily_limit ?: 50000);
        $model->status = $request->status ?: 'active';
        $model->is_active = ($model->status === 'active');

        $model->save();

        return redirect()->route($this->route . '.index')->with('success', $request->id ? 'Payment method updated successfully.' : 'Payment method created successfully.');
    }

    public function toggle_status($id)
    {
        $model = PaymentMethod::findOrFail($id);
        $model->is_active = !$model->is_active;
        $model->status = $model->is_active ? 'active' : 'inactive';
        $model->save();

        $state = $model->is_active ? 'Activated 🟢' : 'Deactivated / Rotated Off 🔴';
        return redirect()->back()->with('success', "Payment Gateway {$model->name} ({$model->address}) is now {$state}.");
    }

    public function delete($id)
    {
        $model = PaymentMethod::find($id);
        if ($model) {
            $model->delete();
        }
        return redirect()->route($this->route . '.index')->with('success', 'Payment method deleted successfully.');
    }
}
