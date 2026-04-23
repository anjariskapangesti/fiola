<?php

use Illuminate\Database\Seeder;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get some IT users for PIC
        $itStaff = User::where('email', 'alliq@aiia.co.id')->first();
        $itAdmin = User::where('email', 'administrator@aiia.co.id')->first();
        
        $tickets = [
            // SCENARIO 1: Just Created (Waiting for IT)
            [
                'no_reg' => 'TIK/2404/001',
                'requestor_name' => 'Irfan Anshori',
                'requestor_phone' => '081234567899',
                'requestor_department' => 'HRD&GA',
                'category' => 'Hardware',
                'detail_case' => 'Keyboard laptop tidak berfungsi beberapa tombol.',
                'location' => 'Office 1st Floor',
                'sla' => 'Medium',
                'final_status' => 'created',
                'created_at' => Carbon::now()->subDays(2),
            ],
            // SCENARIO 2: IT Approved (Accepted, On Progress)
            [
                'no_reg' => 'TIK/2404/002',
                'requestor_name' => 'Khusni Setyawan',
                'requestor_phone' => '081234567900',
                'requestor_department' => 'HRD&GA',
                'category' => 'Software',
                'detail_case' => 'Install Adobe Acrobat Pro untuk edit PDF.',
                'location' => 'Office 1st Floor',
                'sla' => 'Low',
                'final_status' => 'IT Approve',
                'is_it_approve' => 1,
                'it_approve_by' => $itStaff ? $itStaff->id : 1,
                'it_approval_date' => Carbon::now()->subDay(),
                'created_at' => Carbon::now()->subDays(3),
            ],
            // SCENARIO 3: Pending (Waiting for Parts/External)
            [
                'no_reg' => 'TIK/2404/003',
                'requestor_name' => 'Indra Pasurya',
                'requestor_phone' => '081234567901',
                'requestor_department' => 'HRD&GA',
                'category' => 'Network',
                'detail_case' => 'Wifi di ruangan rapat sering terputus.',
                'location' => 'Meeting Room A',
                'sla' => 'High',
                'final_status' => 'Pending',
                'is_it_approve' => 1,
                'is_on_progress' => 1,
                'it_approve_by' => $itStaff ? $itStaff->id : 1,
                'it_approval_date' => Carbon::now()->subDays(2),
                'on_progress_by' => $itStaff ? $itStaff->id : 1,
                'on_progress_date' => Carbon::now()->subDay(),
                'on_progress_note' => 'Menunggu penggantian Access Point baru dari vendor.',
                'created_at' => Carbon::now()->subDays(5),
            ],
            // SCENARIO 4: Finished (Resolved)
            [
                'no_reg' => 'TIK/2404/004',
                'requestor_name' => 'Ziyan Awaliyah Ritonga',
                'requestor_phone' => '081234567902',
                'requestor_department' => 'HRD&GA',
                'category' => 'Application',
                'detail_case' => 'Lupa password HRIS.',
                'location' => 'Office 2nd Floor',
                'sla' => 'High',
                'final_status' => 'Finished',
                'is_it_approve' => 1,
                'is_finish' => 1,
                'it_approve_by' => $itAdmin ? $itAdmin->id : 1,
                'it_approval_date' => Carbon::now()->subDays(4),
                'finish_by' => $itAdmin ? $itAdmin->id : 1,
                'finish_date' => Carbon::now()->subDays(3),
                'solution' => 'Password sudah di-reset dan diinformasikan ke user.',
                'created_at' => Carbon::now()->subDays(4),
            ],
            // SCENARIO 5: Rejected (Not IT Issue / Policy)
            [
                'no_reg' => 'TIK/2404/005',
                'requestor_name' => 'Nikmatul Maulita',
                'requestor_phone' => '081234567904',
                'requestor_department' => 'IRL&GA',
                'category' => 'Hardware',
                'detail_case' => 'Minta ganti mouse gaming.',
                'location' => 'Office 1st Floor',
                'sla' => 'Low',
                'final_status' => 'Rejected',
                'is_it_approve' => 0,
                'it_approve_by' => $itAdmin ? $itAdmin->id : 1,
                'it_approval_date' => Carbon::now()->subDay(),
                'it_note' => 'Permintaan ditolak karena tidak sesuai dengan standar hardware kantor.',
                'created_at' => Carbon::now()->subDays(1),
            ],
            // SCENARIO 6: Finished with Review
            [
                'no_reg' => 'TIK/2404/006',
                'requestor_name' => 'Ahmad Rizky Rifai',
                'requestor_phone' => '081234567903',
                'requestor_department' => 'HRD&GA',
                'category' => 'Hardware',
                'detail_case' => 'Printer macet (paper jam).',
                'location' => 'Copy Center',
                'sla' => 'Medium',
                'final_status' => 'Finished',
                'is_it_approve' => 1,
                'is_finish' => 1,
                'it_approve_by' => $itStaff ? $itStaff->id : 1,
                'it_approval_date' => Carbon::now()->subDays(10),
                'finish_by' => $itStaff ? $itStaff->id : 1,
                'finish_date' => Carbon::now()->subDays(9),
                'solution' => 'Membersihkan sisa kertas di dalam roller printer.',
                'review' => 5,
                'comment' => 'Respon cepat dan ramah. Terima kasih!',
                'created_at' => Carbon::now()->subDays(10),
            ],
        ];

        foreach ($tickets as $ticketData) {
            $ticketData['id'] = Str::uuid()->toString();
            Ticket::create($ticketData);
        }
    }
}
