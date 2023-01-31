<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PreferenceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Preference::truncate();
        $pref = new \App\Preference();
        $pref->name = 'so_textbox';
        $pref->status = 1;
        $pref->save();

        $pref = new \App\Preference();
        $pref->name = 'po_textbox';
        $pref->status = 1;
        $pref->save();

        $pref = new \App\Preference();
        $pref->name = 'so_auto';
        $pref->status = 1;
        $pref->save();

        $pref = new \App\Preference();
        $pref->name = 'po_auto';
        $pref->status = 1;
        $pref->save();


        $pref = new \App\Preference();
        $pref->name = 'billing_address_fill';
        $pref->status = '';
        $pref->save();

        $pref = new \App\Preference();
        $pref->name = 'delivery_address_fill';
        $pref->status = '';
        $pref->save();

        $pref = new \App\Preference();
        $pref->name = 'company_details_fill';
        $pref->status = '';
        $pref->save();

        $pref = new \App\Preference();
        $pref->name = 'tac_so_fill';
        $pref->status = '';
        $pref->save();

        $pref = new \App\Preference();
        $pref->name = 'tac_po_fill';
        $pref->status = '';
        $pref->save();

        $pref = new \App\Preference();
        $pref->name = 'account_no';
        $pref->status = '';
        $pref->save();

        $pref = new \App\Preference();
        $pref->name = 'account_name';
        $pref->status = '';
        $pref->save();

        $pref = new \App\Preference();
        $pref->name = 'tin_no';
        $pref->status = '';
        $pref->save();

        $pref = new \App\Preference();
        $pref->name = 'expiration';
        $pref->status = '';
        $pref->save();

        $pref = new \App\Preference();
        $pref->name = 'company_name';
        $pref->status = 'Sample Name';
        $pref->save();
    }
}
