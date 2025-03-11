<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = [
            ['subject_id' => 'sub1','name' => 'Math'],
            ['subject_id' => 'sub2','name' => 'science'],
            ['subject_id' => 'sub3','name' => 'Arabic'],
            ['subject_id' => 'sub4','name' => 'English'],
            ['subject_id' => 'sub5','name' => 'History'],
        ];

        foreach($subjects as $subject){
            Subject::create($subject);
        }
    }
}
