<?php

namespace App\Http\Controllers;

use App\PrintSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
class PrintSettingController extends Controller
{
    public function index()
    {
        $build      = [];

        $print_setting = PrintSetting::query()->first();

        $build = collect($print_setting);

        return view('print_setting', compact('build'));

    }

    public function update(Request $request)
    {
        $data = $request->input();

        $files = $request->file('header_logo');

        $files2 = $request->file('file_system');

        // print_r($files);

        if($files){

            try {
                $filename = 1 . '_' . $files->getClientOriginalName();
                $filename = str_replace(' ', '_', $filename);
                $filePath = $files->storeAs('/print/header', $filename);
                $PrintSetting = PrintSetting::updateOrInsert(
                            ['id' => 1],
                            [
                                'header_logo' => $filename,
                                'header_logo_path' => "app/public/print/header/",
                            ]
                        );
            } catch (\Exception $e) {
                print_r('Error storing file: ' . $e->getMessage());
            }
        }else{
            $filename = $request->header_logo;
        }

        if($files2){
            try {
                $filename2 = 1 . '_' . $files2->getClientOriginalName();
                $filename2 = str_replace(' ', '_', $filename2);

                $filePath = $files2->storeAs('/print/system_logo', $filename2);
                $PrintSetting = PrintSetting::updateOrInsert(
                            ['id' => 1],
                            [
                                 'system_logo' => $filename2,
                                'system_logo_path' => "app/public/print/system_logo/",
                            ]
                        );
            } catch (\Exception $e) {
                print_r('Error storing file: ' . $e->getMessage());
            }
        }else{
            $filename2 = $request->header_logo;
        }

        $PrintSetting = PrintSetting::updateOrInsert(
            ['id' => 1],
            [
                'address' => $data['address'],
                'rma_team' => $data['rma_team'],
                'sales1' => $data['sales1'],
                'sales2' => $data['sales2'],
                'email' => $data['email'],
            ]
        );

        return 'success';

    }
}
