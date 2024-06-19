<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Ticket;
use App\Models\TicketPhoto;
use App\Models\Department;
use App\Models\Alert;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use DataTables;
use Auth;

class TicketController extends Controller
{
    public function list()
    {
        return view('website.pages.ticket.list');
    }

    public function list_ajax(Request $request)
    {
        $data = Ticket::select('tickets.*', 'users.name as it_name', 'users.nohp as it_phone')
                        ->leftJoin('public.users', 'tickets.it_approve_by', 'public.users.id')
                        ->with('ticket_photos')
                        ->orderBy('created_at', 'DESC');

        return DataTables::eloquent($data)->make(true);
    }

    public function create()
    {
        $departments = Department::select('departments.*')
                                ->orderBy('name', 'ASC')
                                ->get();

        return view('website.pages.ticket.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $year = date('y');
        $month = date('m');
        $lastForm = DB::table('tickets')
            ->select('no_reg')
            ->orderBy('no_reg', 'desc')
            ->first();
        $lastNumber = ($lastForm) ? substr($lastForm->no_reg, -3) : '000';

        $lastMonth = ($lastForm) ? substr($lastForm->no_reg, 6, 2) : '00';
        if ($lastMonth !== $month) {
            $lastNumber = '000';
        }
        $newNumber = str_pad((intval($lastNumber) + 1), strlen($lastNumber), '0', STR_PAD_LEFT);
        $no_reg = 'TIK/' . $year . $month . '/' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        try {
            $ticket = Ticket::create([
                'no_reg' => $no_reg,
                'requestor_name' => $request->requestor_name,
                'requestor_phone' => $request->requestor_phone,
                'requestor_department' => $request->requestor_department,
                'detail_case' => $request->detail_case,
                'category' => $request->category,
                'location' => $request->location,
                'sla' => $request->sla,
                'final_status' => 'created',
            ]);
            $ticket->save();

            if ($request->hasFile('lampiran')) {
                $lampiranExtension = $request->lampiran->getClientOriginalExtension();
                $lampiranFileName = 'TIK_' . $year . $month . '_' . str_pad($newNumber, 3, '0', STR_PAD_LEFT) . '.' . $lampiranExtension;
                $lampiranPath = $request->lampiran->storeAs('lampiran', $lampiranFileName, 'public');

                $ticket_photos = TicketPhoto::create([
                    'ticket_id' => $ticket->id,
                    'path' => $lampiranFileName
                ]);
            }

            $isi = "TICKET\n";
            $isi .= "*TUNGGU APPROVE IT*";
            $isi .= "\n\nREQUESTOR";
            $isi .= "\nNama : *" . $request->requestor_name . "*";
            $isi .= "\nDepartment : *" . $request->requestor_department . "*";
            $isi .= "\n\nDetail Case : " . $request->detail_case;
            $isi .= "\n\nNote : Dear Tim ITD, mohon cek FIOLA ada ticket menunggu.";

            $nomors = Alert::where('role', 'IT')->get();

            foreach ($nomors as $nomor) {
                $token = "v2n49drKeWNoRDN4jgqcdsR8a6bcochcmk6YphL6vLcCpRZdV1";
                $message = sprintf("----------FIOLA----------%c$isi%c------------------------- ", 10, 10);
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://app.ruangwa.id/api/send_message',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => 'token=' . $token . '&number=' . $nomor->nohp . '&message=' . $message,
                ));
                $response = curl_exec($curl);
                curl_close($curl);
            }

            return redirect()->route('website.ticket.list')->with('success', 'Create Successfully');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function it_approval()
    {
        return view('website.pages.ticket.it_approval');
    }

    public function it_approval_ajax(Request $request)
    {
        $data = Ticket::select('tickets.*', 'users.name as it_name', 'users.nohp as it_phone')
                        ->leftJoin('public.users', 'tickets.it_approve_by', 'public.users.id')
                        ->with('ticket_photos')
                        ->orderBy('created_at', 'DESC');

        return DataTables::eloquent($data)->make(true);
    }

    public function it_approve(Request $request)
    {
        $id = $request->id;
        $type = $request->type;

        $ticket = Ticket::findOrFail($id);
        
        if ($type == 'approve') {
            $ticket->is_it_approve = 1;
            $ticket->final_status = 'IT Approve';
            $ticket->it_note = $request->it_note;
            $ticket->it_approve_by = Auth::user()->id;
            $return = "Approve Successfully";
        } else if ($type == 'progress') {
            $ticket->is_on_progress = 1;
            $ticket->final_status = 'Pending';
            $ticket->on_progress_note = $request->on_progress_note;
            $ticket->on_progress_by = Auth::user()->id;
            $ticket->on_progress_date = Carbon::now();
            $return = "Progress Successfully";
        } else if ($type == 'finish') {
            $ticket->is_finish = 1;
            $ticket->is_confirm = 0;
            $ticket->final_status = 'Finished';
            $ticket->solution = $request->finish_note;
            $ticket->finish_by = Auth::user()->id;
            $ticket->finish_date = Carbon::now();
            $return = "Finish Successfully";
        } else {
            $ticket->is_it_approve = 0;
            $ticket->is_confirm = 0;
            $ticket->final_status = 'Rejected';
            $ticket->it_note = $request->it_note;
            $ticket->is_finish = 0;
            $ticket->it_approve_by = Auth::user()->id;
            $return = "Reject Successfully";
        }
        $ticket->it_approval_date = Carbon::now();
        $ticket->save();
        
        if ($request->notifikasi == 'Ya') {
            $isi = "TICKET\n";
            $isi .= "\nNo Reg : " . $ticket->no_reg;
            $isi .= "\nREQUESTOR";
            $isi .= "\nNama : *" . $ticket->requestor_name . "*";
            $isi .= "\nDepartment : *" . $ticket->requestor_department . "*";
            $isi .= "\n\nDetail Case : " . $ticket->detail_case;

            if ($type == 'finish') {
                $isi .= "\nSolution : " . $request->finish_note;
                $isi .= "\n\nNote : Dear User, Tiket anda sudah selesai.";

                $isi .= "\n\nFinished by : " . Auth::user()->name;

                $isi .= "\n\nMohon beri penilaian atas support dari Tim ITD dengan mengakses link berikut :";
                $isi .= "\nhttps://fiola-qa.aiia.co.id/ticket/review/" . $ticket->id;
                $isi .= "\n\nTerima Kasih.";
            } elseif ($type == 'approve') {
                $isi .= "\n\nNote : Dear User, Tiket anda sudah diterima oleh Tim ITD.";

                $isi .= "\n\nAccepted by : " . Auth::user()->name;
            }

            $token = "v2n49drKeWNoRDN4jgqcdsR8a6bcochcmk6YphL6vLcCpRZdV1";
            $message = sprintf("----------FIOLA----------%c$isi%c------------------------- ", 10, 10);
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://app.ruangwa.id/api/send_message',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => 'token=' . $token . '&number=' . $ticket->requestor_phone . '&message=' . $message,
            ));

            $response = curl_exec($curl);
            curl_close($curl);
        }
        return $return;
    }

    public function review($id)
    {
        $ticket = Ticket::with('ticket_photos')->findOrFail($id);

        return view('website.pages.ticket.review', compact('ticket'));
    }

    public function rate(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        $ticket->update([
            'review' => $request->review,
            'comment' => $request->comment,
        ]);

        return redirect()->back()->with('success', 'Update Successfully');
    }
}
