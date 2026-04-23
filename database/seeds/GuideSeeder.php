<?php

use Illuminate\Database\Seeder;
use App\Models\Guide;

class GuideSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $guides = [
            [
                'form_name' => 'Account Request',
                'lampiran' => 'guide/guide_account.pdf',
            ],
            [
                'form_name' => 'Folder Access Request',
                'lampiran' => 'guide/guide_folder_access.pdf',
            ],
            [
                'form_name' => 'New Folder Request',
                'lampiran' => 'guide/guide_new_folder.pdf',
            ],
            [
                'form_name' => 'Software Installation',
                'lampiran' => 'guide/guide_software.pdf',
            ],
            [
                'form_name' => 'Hardware Request',
                'lampiran' => 'guide/guide_hardware.pdf',
            ],
            [
                'form_name' => 'VPN Access',
                'lampiran' => 'guide/guide_vpn.pdf',
            ],
        ];

        foreach ($guides as $guide) {
            Guide::updateOrCreate(
                ['form_name' => $guide['form_name']],
                $guide
            );
        }
    }
}
