<?php

namespace Database\Seeders;

use App\Models\District;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $districts = [
            ['name' => 'Jammu', 'ulbs' => [
                ['name' => 'Jammu (JMC)', 'status' => 'Municipal Corporation', 'code' => '800071'],
                ['name' => 'Akhnoor', 'status' => 'Municipal Committee', 'code' => '800069'],
                ['name' => 'Gho-Manhasan', 'status' => 'Municipal Committee', 'code' => '800073'],
                ['name' => 'Bishnah', 'status' => 'Municipal Committee', 'code' => '800075'],
                ['name' => 'Arnia', 'status' => 'Municipal Committee', 'code' => '800076'],
                ['name' => 'R.S. Pura', 'status' => 'Municipal Committee', 'code' => '800074'],
                ['name' => 'Khour', 'status' => 'Municipal Committee', 'code' => '800070'],
                ['name' => 'Jourian', 'status' => 'Municipal Committee', 'code' => '800068'],
                ['name' => 'Jammu Cantonment', 'status' => 'Cantonement Board', 'code' => '800072'],
            ]],
            ['name' => 'Samba', 'ulbs' => [
                ['name' => 'Samba', 'status' => 'Municipal Council', 'code' => '800079'],
                ['name' => 'Vijaypur', 'status' => 'Municipal Committee', 'code' => '800078'],
                ['name' => 'Bari-Brahamana', 'status' => 'Municipal Committee', 'code' => '800077'],
                ['name' => 'Ramgarh', 'status' => 'Municipal Committee', 'code' => '800080'],
            ]],
            ['name' => 'Kathua', 'ulbs' => [
                ['name' => 'Kathua', 'status' => 'Municipal Council', 'code' => '800084'],
                ['name' => 'Basholi', 'status' => 'Municipal Committee', 'code' => '800082'],
                ['name' => 'Lakhanpur', 'status' => 'Municipal Committee', 'code' => '800083'],
                ['name' => 'Billawar', 'status' => 'Municipal Committee', 'code' => '800081'],
                ['name' => 'Parole', 'status' => 'Municipal Committee', 'code' => '800085'],
                ['name' => 'Hiranagar', 'status' => 'Municipal Committee', 'code' => '800086'],
            ]],
            ['name' => 'Rajouri', 'ulbs' => [
                ['name' => 'Rajouri', 'status' => 'Municipal Council', 'code' => '800065'],
                ['name' => 'Sunderbani', 'status' => 'Municipal Committee', 'code' => '800067'],
                ['name' => 'Kalakote', 'status' => 'Municipal Committee', 'code' => '900150'],
                ['name' => 'Nowshera', 'status' => 'Municipal Committee', 'code' => '800066'],
                ['name' => 'Thanamandi', 'status' => 'Municipal Committee', 'code' => '800064'],
            ]],
            ['name' => 'Kishtwar', 'ulbs' => [
                ['name' => 'Kishtwar', 'status' => 'Municipal Council', 'code' => '800054'],
            ]],
            ['name' => 'Udhampur', 'ulbs' => [
                ['name' => 'Udhampur', 'status' => 'Municipal Council', 'code' => '800055'],
                ['name' => 'Chenani', 'status' => 'Municipal Committee', 'code' => '800057'],
                ['name' => 'Ramnagar', 'status' => 'Municipal Committee', 'code' => '800058'],
            ]],
            ['name' => 'Reasi', 'ulbs' => [
                ['name' => 'Reasi', 'status' => 'Municipal Council', 'code' => '800059'],
                ['name' => 'Katra', 'status' => 'Municipal Committee', 'code' => '800061'],
            ]],
            ['name' => 'Doda', 'ulbs' => [
                ['name' => 'Doda', 'status' => 'Municipal Council', 'code' => '800049'],
                ['name' => 'Thathri', 'status' => 'Municipal Committee', 'code' => '900181'],
                ['name' => 'Bhaderwah', 'status' => 'Municipal Committee', 'code' => '800050'],
            ]],
            ['name' => 'Ramban', 'ulbs' => [
                ['name' => 'Ramban', 'status' => 'Municipal Council', 'code' => '800052'],
                ['name' => 'Batote', 'status' => 'Municipal Committee', 'code' => '800053'],
                ['name' => 'Banihal', 'status' => 'Municipal Committee', 'code' => '800051'],
            ]],
            ['name' => 'Poonch', 'ulbs' => [
                ['name' => 'Poonch', 'status' => 'Municipal Council', 'code' => '800062'],
                ['name' => 'Surankote', 'status' => 'Municipal Committee', 'code' => '800063'],
            ]],
            ['name' => 'Srinagar', 'ulbs' => [
                ['name' => 'Srinagar (SMC)', 'status' => 'Municipal Corportation', 'code' => '800013'],
                ['name' => 'Badamibagh Cantonment', 'status' => 'Cantonement Board', 'code' => '800014'],
            ]],
            ['name' => 'Anantnag', 'ulbs' => [
                ['name' => 'Anantnag', 'status' => 'Municipal Council', 'code' => '800033'],
                ['name' => 'Achabal', 'status' => 'Municipal Committee', 'code' => '800034'],
                ['name' => 'Bijbehara', 'status' => 'Municipal Committee', 'code' => '800030'],
                ['name' => 'Kokernag', 'status' => 'Municipal Committee', 'code' => '800037'],
                ['name' => 'Mattan', 'status' => 'Municipal Committee', 'code' => '800032'],
                ['name' => 'Qazigund', 'status' => 'Municipal Committee', 'code' => '800038'],
                ['name' => 'Aishmuguam', 'status' => 'Municipal Committee', 'code' => '800029'],
                ['name' => 'Seer Hamdam', 'status' => 'Municipal Committee', 'code' => '800035'],
                ['name' => 'Pahalgam', 'status' => 'Municipal Committee', 'code' => '800028'],
                ['name' => 'Dooru-Veerinag', 'status' => 'Municipal Committee', 'code' => '800039'],
            ]],
            ['name' => 'Kulgam', 'ulbs' => [
                ['name' => 'Kulgam', 'status' => 'Municipal Council', 'code' => '800040'],
                ['name' => 'Devsar', 'status' => 'Municipal Committee', 'code' => '800046'],
                ['name' => 'Yaripora', 'status' => 'Municipal Committee', 'code' => '800043'],
                ['name' => 'Frisal', 'status' => 'Municipal Committee', 'code' => '800042'],
            ]],
            ['name' => 'Pulwama', 'ulbs' => [
                ['name' => 'Pulwama', 'status' => 'Municipal Council', 'code' => '800026'],
                ['name' => 'Pampore', 'status' => 'Municipal Committee', 'code' => '800022'],
                ['name' => 'Tral', 'status' => 'Municipal Committee', 'code' => '800025'],
                ['name' => 'Khrew', 'status' => 'Municipal Committee', 'code' => '800023'],
                ['name' => 'Awantipora', 'status' => 'Municipal Committee', 'code' => '800024'],
            ]],
            ['name' => 'Shopian', 'ulbs' => [
                ['name' => 'Shopian', 'status' => 'Municipal Council', 'code' => '800027'],
            ]],
            ['name' => 'Ganderbal', 'ulbs' => [
                ['name' => 'Ganderbal', 'status' => 'Municipal Council', 'code' => '800015'],
            ]],
            ['name' => 'Budgam', 'ulbs' => [
                ['name' => 'Budgam', 'status' => 'Municipal Council', 'code' => '800019'],
                ['name' => 'Khansahib', 'status' => 'Municipal Committee', 'code' => '800018'],
                ['name' => 'Magam', 'status' => 'Municipal Committee', 'code' => '800016'],
                ['name' => 'Beerwah', 'status' => 'Municipal Committee', 'code' => '800017'],
                ['name' => 'Chadoora', 'status' => 'Municipal Committee', 'code' => '800020'],
                ['name' => 'Charie Sharief', 'status' => 'Municipal Committee', 'code' => '800021'],
            ]],
            ['name' => 'Baramulla', 'ulbs' => [
                ['name' => 'Baramulla', 'status' => 'Municipal Council', 'code' => '800006'],
                ['name' => 'Sopore', 'status' => 'Municipal Council', 'code' => '800003'],
                ['name' => 'Kunzer', 'status' => 'Municipal Committee', 'code' => '800008'],
                ['name' => 'Pattan', 'status' => 'Municipal Committee', 'code' => '800005'],
                ['name' => 'Uri', 'status' => 'Municipal Committee', 'code' => '800007'],
                ['name' => 'Tangmarg/Gulmarg', 'status' => 'Municipal Committee', 'code' => '800009'],
                ['name' => 'Watragam', 'status' => 'Municipal Committee', 'code' => '800004'],
            ]],
            ['name' => 'Bandipora', 'ulbs' => [
                ['name' => 'Bandipora', 'status' => 'Municipal Council', 'code' => '800010'],
                ['name' => 'Sumbal', 'status' => 'Municipal Committee', 'code' => '800012'],
                ['name' => 'Hajan', 'status' => 'Municipal Committee', 'code' => '800011'],
            ]],
            ['name' => 'Kupwara', 'ulbs' => [
                ['name' => 'Kupwara', 'status' => 'Municipal Council', 'code' => '800001'],
                ['name' => 'Handwara', 'status' => 'Municipal Committee', 'code' => '800002'],
                ['name' => 'Langate', 'status' => 'Municipal Committee', 'code' => '900173'],
            ]],
        ];

        foreach ($districts as $districtData) {
            $district = District::updateOrCreate(
                ['name' => $districtData['name']],
                ['name' => $districtData['name']]
            );

            foreach ($districtData['ulbs'] as $ulbData) {
                $district->ulbs()->updateOrCreate(
                    ['code' => $ulbData['code']],
                    [
                        'name' => $ulbData['name'],
                        'status' => $ulbData['status'],
                    ]
                );
            }
        }
    }
}
