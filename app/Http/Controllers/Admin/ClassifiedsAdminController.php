<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ListingApprovedMail;
use App\Mail\ListingRejectedMail;

class ClassifiedsAdminController extends Controller
{
    public function pending(Request $request)
    {
        $items = Listing::with(['user','images'])->where('status','pending')->latest()->paginate(20);
        return view('admin.classifieds.pending', compact('items'));
    }

    public function approved(Request $request)
    {
        $items = Listing::with(['user','images'])->where('status','approved')->latest()->paginate(20);
        return view('admin.classifieds.approved', compact('items'));
    }

    public function approve(Listing $listing)
    {
        $listing->update(['status'=>'approved']);
        if ($listing->user && $listing->user->email) {
            Mail::to($listing->user->email)->send(new ListingApprovedMail($listing));
        }
        return back()->with('status','Anúncio aprovado.');
    }

    public function reject(Listing $listing)
    {
        $listing->update(['status'=>'rejected']);
        if ($listing->user && $listing->user->email) {
            Mail::to($listing->user->email)->send(new ListingRejectedMail($listing));
        }
        return back()->with('status','Anúncio rejeitado.');
    }

    public function destroy(Listing $listing)
    {
        $listing->delete();
        return back()->with('status','Anúncio removido.');
    }
}
