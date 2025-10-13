<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'    => ['nullable','string','max:255'],
            'phone'   => ['required','string','max:255'],
            'message' => ['nullable','string','max:5000'],
        ]);

        $data['status'] = 'new';

        $lead = Lead::create($data);

        if ($request->wantsJson()) {
            return response()->json(['ok'=>true,'id'=>$lead->id]);
        }
        return back()->with('success', 'Заявка отправлена. Мы свяжемся с вами!');
    }
}
