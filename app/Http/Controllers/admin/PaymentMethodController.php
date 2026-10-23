<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use App\Models\Deposit;
use Illuminate\Http\Request;

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
        $this->validate($request,[
            'name'=> 'required',
            'address'=> 'required',
        ]);

        if ($request->id){
            $model = PaymentMethod::findOrFail($request->id);
        }else{
            $model = new PaymentMethod();
        }

        $path = uploadImage(false ,$request, 'photo', 'upload/setting/', null, null ,$model->photo);
        $model->photo = $path ?? $model->photo;
        $model->name = $request->name;
        $model->address = $request->address;
        $model->account_title = $request->account_title ?? $request->name;
        $model->account_type = $request->account_type ?? 'wallet';
        $model->daily_limit = (float)($request->daily_limit ?: 50000);
        $model->status = $request->status ?? 'active';
        $model->is_active = ($model->status === 'active');

        $model->save();
        return redirect()->route($this->route.'.index')->with('success', $request->id ? 'Payment method updated successfully.' : 'Payment method created successfully.');
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
        return redirect()->route($this->route.'.index')->with('success','Payment method deleted successfully.');
    }
}
