<?php

namespace App\Http\Controllers;

use App\Models\Expropriation;
use App\Models\ExpropriationDetail;
use App\Models\ExpropriationHistory;
use App\Models\PropertyItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpropriationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $expropriations = Expropriation::all();
        return view('expropriation.index', compact('expropriations'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('expropriation.create', [
            'products' => PropertyItem::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $expo = Expropriation::create([
            'property_type_id' => $request->property_type_id,
            'citizen_id' => $request->citizen_id,
            'done_by' => auth()->user()->id,
            'amount' => $request->total,
            'description' => $request->note,
            'province_id' => $request->province_id,
            'district_id' => $request->district_id,
            'sector_id' => $request->sector_id,
        ]);

        foreach ($request->product_id as $key => $product) {
            ExpropriationDetail::create([
                'expropriation_id' => $expo->id,
                'property_item_id' => $product,
                'property_type_id' => $request->property_type_id,
                'quantity' => $request->Quantity[$key],
                'price' => $request->UnitPrice[$key],
            ]);
        }

        return redirect()->route('admin.expropriations.index')->with('success', 'Expropriation created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Expropriation  $expropriation
     * @return \Illuminate\Http\Response
     */
    public function show(Expropriation $expropriation)
    {
        return view('expropriation.show', [
            'expropriation' => $expropriation,
            'histories' => $expropriation->histories()->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Expropriation  $expropriation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Expropriation $expropriation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Expropriation  $expropriation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Expropriation $expropriation)
    {
        //
    }

    public function submit(Expropriation $expropriation)
    {
        $expropriation->update([
            'status' => Expropriation::SUBMITTED,
        ]);
        return redirect()->route('admin.expropriations.index')->with('success', 'Expropriation submitted successfully');
    }

    public function review(Request $request,Expropriation $Expropriation): \Illuminate\Http\RedirectResponse
    {
        $user = auth()->user();

        if (in_array($Expropriation->status, [Expropriation::SUBMITTED])
            && in_array($request->status, [Expropriation::PROPOSE_TO_REJECT, Expropriation::PROPOSE_TO_RETURN_BACK, Expropriation::PROPOSE_TO_APPROVE, Expropriation::REJECTED])
            && $user->can('Review Expropriations')) {
            if ($request->status == Expropriation::REJECTED)
                return $this->approveExpropriation($request, $Expropriation);
            else
                return $this->reviewExpropriation($request, $Expropriation);
        } else if ($Expropriation->status == Expropriation::REVIEWED
            && in_array($request->status, [Expropriation::APPROVED, Expropriation::REJECTED, Expropriation::RETURN_BACK_TO_REVIEW, Expropriation::RETURN_BACK])
            && $user->can('Approve Expropriations')) {
            return $this->approveExpropriation($request, $Expropriation);
        } else {
            abort(403);
        }
    }
        function reviewExpropriation($request, Expropriation $expropriation): \Illuminate\Http\RedirectResponse
        {
            $user = auth()->user();
            DB::beginTransaction();
            $expropriation->status = Expropriation::REVIEWED;
            $expropriation->review_decision = $request->status;
            $expropriation->save();
            $this->storeHistory($request, $expropriation, $user, $request->status, $request->comment, $request->message);
            DB::commit();
            return redirect()->back()->with("success", "Review is stored Successfully");
        }
        public function storeHistory($request, Expropriation $expropriation,$user,$status,$comment,$message_to_applicant,$is_comment=1)
        {
            if ($request->hasFile('attachment')){
                $file=$request->file('attachment');
                $dir = FileManager::APPROVAL_ATTACHMENT_DIR;
                $path = $file->store($dir);
                $attachment_name = str_replace($dir, '', $path);
            }

            $history = new ExpropriationHistory();
            $history->expropriation_id = $expropriation->id;
            $history->user_id = $user->id;
            $history->status = $status;
            $history->comment = $comment;
            $history->is_comment = $is_comment;
            $history->external_comment = $message_to_applicant;
            $history->attachments = $attachment_name ?? null;
            $history->save();
        }
}
