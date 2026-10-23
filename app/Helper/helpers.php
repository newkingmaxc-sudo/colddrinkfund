<?php


use App\Models\Purchase;

if (!function_exists('main_root')) {
    function main_root()
    {
        return "public";
    }
}

if (!function_exists('admin_file_root')) {
    function admin_file_root()
    {
        return "admin";
    }
}

if (!function_exists('frontend_file_root')) {
    function frontend_file_root()
    {
        return "public/frontend";
    }
}

if (!function_exists('admin')) {
    function admin()
    {
        return \Illuminate\Support\Facades\Auth::guard('admin');
    }
}

if (!function_exists('price')) {
    function price($price)
    {
        return 'Rs. ' . number_format((float)$price, 2);
    }
}

if (!function_exists('currency_symbol')) {
    function currency_symbol()
    {
        return 'Rs.';
    }
}

if (!function_exists('currency_code')) {
    function currency_code()
    {
        return 'PKR';
    }
}

if (!function_exists('user')) {
    function user()
    {
        return \Illuminate\Support\Facades\Auth::user();
    }
}

if (!function_exists('not_found_img')) {
    function not_found_img()
    {
        return 'public/common/img/404.png';
    }
}

if (!function_exists('success_redirect')) {
    function success_redirect($route, $type)
    {
        return redirect()->route($route)->with('success', "Item $type Successfully.");
    }
}

if (!function_exists('error_redirect')) {
    function error_redirect($route, $type, $message)
    {
        return redirect()->route($route)->with($type, $message);
    }
}


if (!function_exists('uploadImage')) {
    function uploadImage($true_II_false_normal, $request, $input, $dir, $w = null, $h = null, $oldInput = null)
    {
        $path = public_path(str_replace('/public/', '', $oldInput));
        if ($request->hasFile($input)) {
            if (File::exists($path)) {
                File::delete($path);
            }
        }

        if ($request->hasFile($input)) {
            $file = $request->file($input);
            $namewithextension = $file->getClientOriginalName(); //Name with extension 'filename.jpg'
            $name = explode('.', $namewithextension)[0]; // Filename 'filename'
            $extension = $file->getClientOriginalExtension();
            $file_name = time() . Str::random(3) . '.' . $extension;

            if ($true_II_false_normal) {
                $destinationPath = public_path($dir);
                $imgFile = Image::make($request->file($input)->getRealPath());
                $imgFile->resize($w, $h, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath . '/' . $file_name);
            } else {
                $request->file($input)->move(public_path($dir), $file_name);
            }

            \Artisan::call('view:clear');
            \Artisan::call('cache:clear');
            $path = '/public/' . $dir . $file_name;
            return $path;
        }
        return null;
    }
}

if (!function_exists('deleteImage')) {
    function deleteImage($oldInput)
    {
        $path = public_path(str_replace('/public/', '', $oldInput));
        if (File::exists($path)) {
            File::delete($path);
        }
        return true;
    }
}

if (!function_exists('view_image')) {
    function view_image($imageName)
    {
        if (empty($imageName) || !is_string($imageName)) {
            return '/common/img/404.png';
        }
        if (str_starts_with($imageName, 'http://') || str_starts_with($imageName, 'https://')) {
            return $imageName;
        }
        $clean = preg_replace('#^/?public/#', '', $imageName);
        return '/' . ltrim($clean, '/');
    }
}


if (!function_exists('createSlug')) {
    function createSlug($table_model, $title, $id = 0)
    {
        $slug = Str::slug($title);
        $allSlugs = getRelatedSlugs($table_model, $slug, $id);
        if (!$allSlugs->contains('slug', $slug)) {
            return $slug;
        }
        // Just append numbers like a savage until we find not used.
        for ($i = 1; $i <= 10; $i++) {
            $newSlug = $slug . '-' . $i;
            if (!$allSlugs->contains('slug', $newSlug)) {
                return $newSlug;
            }
        }
        throw new \Exception('Can not create a unique slug');
    }
}
//$object->slug = Helper::createSlug('\Category',$request->name);

if (!function_exists('getRelatedSlugs')) {
    function getRelatedSlugs($table_model, $slug, $id = 0)
    {
        $model_name = "App\Models" . $table_model;


        $data = $model_name::where('slug', 'like', $slug . '%')
            ->where('id', '<>', $id)
            ->get();
        return $data;
    }
}


if (!function_exists('setting')) {
    function setting($column, $default = null)
    {
        try {
            $cachedSettings = \Illuminate\Support\Facades\Cache::remember('app_system_settings', 60, function () {
                return \App\Models\Setting::first();
            });
        } catch (\Throwable $e) {
            return $default !== null ? $default : "Data Is Empty";
        }

        if ($cachedSettings && isset($cachedSettings->{$column})) {
            return $cachedSettings->{$column};
        }

        return $default !== null ? $default : "Data Is Empty";
    }
}


if (!function_exists('my_vips')) {
    function my_vips()
    {
        $purchaseVips = Purchase::where('user_id', \auth()->id())->where('status', 'active')->orderByDesc('id')->get();
        $vids = [];
        foreach ($purchaseVips as $el) {
            array_push($vids, $el->package_id);
        }
        return $vids;
    }
}
if (!function_exists('all_vips')) {
    function all_vips()
    {
        $purchaseVips = Purchase::where('user_id', \auth()->id())->orderByDesc('id')->get();
        $vids = [];
        foreach ($purchaseVips as $el) {
            array_push($vids, $el->package_id);
        }
        return $vids;
    }
}
if (!function_exists('my_inactive_vips')) {
    function my_inactive_vips()
    {
        $purchaseVips = Purchase::where('user_id', \auth()->id())->where('status', 'inactive')->orderByDesc('id')->get();
        $vids = [];
        foreach ($purchaseVips as $el) {
            array_push($vids, $el->package_id);
        }
        return $vids;
    }
}
function translator($text)
{
    return $text;
}

function hourlyamount($validity, $amount)
{
    $calculate_amount = 0;
    if ($amount != 0){
        $calculate_amount = ($amount / $validity) / 24; //24=hour
    }

    return $calculate_amount;
}

if (!function_exists('user_vip_badge')) {
    function user_vip_badge($user = null)
    {
        $user = $user ?: auth()->user();
        if (!$user) {
            return [
                'level'        => 0,
                'name'         => 'Free Member',
                'badge'        => '🌱 Free Tier',
                'color'        => '#15803d',
                'bg'           => 'linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%)',
                'border'       => '#22c55e',
                'icon'         => '🌱',
                'perks'        => 'Daily Check-in Active',
                'payout_speed' => 'Standard'
            ];
        }

        try {
            $maxPackage = \App\Models\Purchase::where('user_id', $user->id)
                ->where('status', 'active')
                ->join('packages', 'purchases.package_id', '=', 'packages.id')
                ->orderByDesc('packages.price')
                ->first(['packages.price', 'packages.name']);

            $price = $maxPackage ? (float)$maxPackage->price : 0;

            if ($price >= 15000) {
                return [
                    'level'        => 4,
                    'name'         => 'Diamond Partner',
                    'badge'        => '👑 Diamond VIP',
                    'color'        => '#3730a3',
                    'bg'           => 'linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%)',
                    'border'       => '#6366f1',
                    'icon'         => '👑',
                    'perks'        => 'Instant Payouts • VIP Club Access',
                    'payout_speed' => '⚡ 5 Minutes'
                ];
            } elseif ($price >= 3500) {
                return [
                    'level'        => 3,
                    'name'         => 'Gold Partner',
                    'badge'        => '🥇 Gold VIP',
                    'color'        => '#854d0e',
                    'bg'           => 'linear-gradient(135deg, #fef08a 0%, #facc15 100%)',
                    'border'       => '#eab308',
                    'icon'         => '🥇',
                    'perks'        => 'Priority Payouts • 5% Team Bonus',
                    'payout_speed' => '⚡ 15 Minutes'
                ];
            } elseif ($price >= 800) {
                return [
                    'level'        => 2,
                    'name'         => 'Silver Distributor',
                    'badge'        => '🥈 Silver VIP',
                    'color'        => '#334155',
                    'bg'           => 'linear-gradient(135deg, #f1f5f9 0%, #cbd5e1 100%)',
                    'border'       => '#94a3b8',
                    'icon'         => '🥈',
                    'perks'        => 'Daily Mining Income Active',
                    'payout_speed' => 'Standard'
                ];
            } elseif ($price >= 350) {
                return [
                    'level'        => 1,
                    'name'         => 'Bronze Bottler',
                    'badge'        => '🥉 Bronze VIP',
                    'color'        => '#9a3412',
                    'bg'           => 'linear-gradient(135deg, #ffedd5 0%, #fed7aa 100%)',
                    'border'       => '#f97316',
                    'icon'         => '🥉',
                    'perks'        => 'Verified Investor',
                    'payout_speed' => 'Standard'
                ];
            }
        } catch (\Exception $e) {
            // fallback
        }

        return [
            'level'        => 0,
            'name'         => 'Free Member',
            'badge'        => '🌱 Free Tier',
            'color'        => '#15803d',
            'bg'           => 'linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%)',
            'border'       => '#22c55e',
            'icon'         => '🌱',
            'perks'        => 'Daily Check-in Active',
            'payout_speed' => 'Standard'
        ];
    }
}


?>
