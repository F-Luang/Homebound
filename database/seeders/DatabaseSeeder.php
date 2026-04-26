<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Pet;
use App\Models\Application;
use App\Models\MedicalRecord;
use App\Models\MeetGreet;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // -------------------------------------------------------
        // 1. USERS
        // -------------------------------------------------------

        // Admin account — use this to log in and manage everything
        $admin = User::create([
            'name' => 'Maria Santos',
            'email' => 'admin@homebound.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Volunteer accounts
        $volunteer1 = User::create([
            'name' => 'Carlos Mendoza',
            'email' => 'carlos@homebound.test',
            'password' => Hash::make('password'),
            'role' => 'volunteer',
        ]);

        $volunteer2 = User::create([
            'name' => 'Ana Reyes',
            'email' => 'ana@homebound.test',
            'password' => Hash::make('password'),
            'role' => 'volunteer',
        ]);

        // Adopter accounts
        $adopter1 = User::create([
            'name' => 'Lena Cruz',
            'email' => 'lena@homebound.test',
            'password' => Hash::make('password'),
            'role' => 'adopter',
        ]);

        $adopter2 = User::create([
            'name' => 'James Tan',
            'email' => 'james@homebound.test',
            'password' => Hash::make('password'),
            'role' => 'adopter',
        ]);

        $adopter3 = User::create([
            'name' => 'Sofia Reyes',
            'email' => 'sofia@homebound.test',
            'password' => Hash::make('password'),
            'role' => 'adopter',
        ]);

        $adopter4 = User::create([
            'name' => 'Marco Lim',
            'email' => 'marco@homebound.test',
            'password' => Hash::make('password'),
            'role' => 'adopter',
        ]);

        // -------------------------------------------------------
        // 2. PETS
        // -------------------------------------------------------

        $biscuit = Pet::create([
            'name' => 'Biscuit',
            'species' => 'dog',
            'breed' => 'Labrador Retriever',
            'age_months' => 36,
            'size' => 'large',
            'activity_level' => 'moderate',
            'good_with_kids' => true,
            'hypoallergenic' => false,
            'is_senior' => false,
            'status' => 'available',
            'bio' => 'Biscuit is a gentle giant who loves everyone he meets. He\'s great with kids and other dogs, and already knows basic commands. His favourite hobby is napping in sunbeams.',
        ]);

        $luna = Pet::create([
            'name' => 'Luna',
            'species' => 'cat',
            'breed' => 'Siamese',
            'age_months' => 24,
            'size' => 'small',
            'activity_level' => 'low',
            'good_with_kids' => true,
            'hypoallergenic' => false,
            'is_senior' => false,
            'status' => 'available',
            'bio' => 'Luna is a calm and elegant Siamese who loves quiet homes. She will follow you around all day and curl up on your lap in the evenings. Best suited as an indoor-only cat.',
        ]);

        $pebbles = Pet::create([
            'name' => 'Pebbles',
            'species' => 'rabbit',
            'breed' => 'Holland Lop',
            'age_months' => 14,
            'size' => 'small',
            'activity_level' => 'low',
            'good_with_kids' => true,
            'hypoallergenic' => true,
            'is_senior' => false,
            'status' => 'available',
            'bio' => 'Pebbles is a sweet Holland Lop who loves to be handled. She is hypoallergenic and great for families with mild allergies. She enjoys fresh vegetables and hopping around in a safe space.',
        ]);

        $mochi = Pet::create([
            'name' => 'Mochi',
            'species' => 'dog',
            'breed' => 'Shih Tzu',
            'age_months' => 84,
            'size' => 'small',
            'activity_level' => 'low',
            'good_with_kids' => true,
            'hypoallergenic' => true,
            'is_senior' => true,
            'status' => 'available',
            'bio' => 'Mochi is a 7-year-old Shih Tzu looking for a calm, loving home to spend his golden years. He is hypoallergenic, fully house-trained, and perfectly happy with short daily walks.',
        ]);

        $noodle = Pet::create([
            'name' => 'Noodle',
            'species' => 'cat',
            'breed' => 'Domestic Shorthair',
            'age_months' => 20,
            'size' => 'small',
            'activity_level' => 'moderate',
            'good_with_kids' => false,
            'hypoallergenic' => false,
            'is_senior' => false,
            'status' => 'available',
            'bio' => 'Noodle is a playful and independent cat who prefers a quieter home without young children. He loves to explore, play with toys, and has a big personality in a small body.',
        ]);

        $thunder = Pet::create([
            'name' => 'Thunder',
            'species' => 'dog',
            'breed' => 'German Shepherd',
            'age_months' => 48,
            'size' => 'large',
            'activity_level' => 'high',
            'good_with_kids' => false,
            'hypoallergenic' => false,
            'is_senior' => false,
            'status' => 'available',
            'bio' => 'Thunder is a trained, energetic German Shepherd best suited for experienced dog owners with an active lifestyle. He needs daily running and mental stimulation, and thrives with a confident handler.',
        ]);

        $cookie = Pet::create([
            'name' => 'Cookie',
            'species' => 'dog',
            'breed' => 'Beagle',
            'age_months' => 18,
            'size' => 'medium',
            'activity_level' => 'moderate',
            'good_with_kids' => true,
            'hypoallergenic' => false,
            'is_senior' => false,
            'status' => 'adopted',
            'bio' => 'Cookie found her forever home! She is a cheerful Beagle who loves sniffing out adventures.',
        ]);

        $kiwi = Pet::create([
            'name' => 'Kiwi',
            'species' => 'bird',
            'breed' => 'Cockatiel',
            'age_months' => 30,
            'size' => 'small',
            'activity_level' => 'moderate',
            'good_with_kids' => true,
            'hypoallergenic' => true,
            'is_senior' => false,
            'status' => 'available',
            'bio' => 'Kiwi is a sociable Cockatiel who loves to whistle tunes and sit on shoulders. He is tame, hand-raised, and does well in lively homes.',
        ]);

        // -------------------------------------------------------
        // 3. MEDICAL RECORDS
        // -------------------------------------------------------

        // Biscuit's records
        MedicalRecord::create([
            'pet_id' => $biscuit->id,
            'recorded_by' => $admin->id,
            'record_type' => 'checkup',
            'record_date' => now()->subMonths(12)->toDateString(),
            'notes' => 'Intake physical exam. Healthy weight, good coat. Microchipped and registered.',
        ]);
        MedicalRecord::create([
            'pet_id' => $biscuit->id,
            'recorded_by' => $admin->id,
            'record_type' => 'vaccination',
            'record_date' => now()->subMonths(6)->toDateString(),
            'notes' => 'Rabies and DHPP boosters administered. No adverse reactions observed.',
        ]);
        MedicalRecord::create([
            'pet_id' => $biscuit->id,
            'recorded_by' => $admin->id,
            'record_type' => 'medication',
            'record_date' => now()->subMonths(3)->toDateString(),
            'notes' => 'Deworming — Pyrantel pamoate administered. Follow-up clear.',
        ]);
        MedicalRecord::create([
            'pet_id' => $biscuit->id,
            'recorded_by' => $admin->id,
            'record_type' => 'checkup',
            'record_date' => now()->subWeeks(2)->toDateString(),
            'notes' => 'Mild skin irritation on left ear. Prescribed antihistamine. Resolved within a week.',
        ]);

        // Luna's records
        MedicalRecord::create([
            'pet_id' => $luna->id,
            'recorded_by' => $admin->id,
            'record_type' => 'checkup',
            'record_date' => now()->subMonths(8)->toDateString(),
            'notes' => 'Intake exam. Healthy female cat. Spayed prior to intake. Microchipped.',
        ]);
        MedicalRecord::create([
            'pet_id' => $luna->id,
            'recorded_by' => $admin->id,
            'record_type' => 'vaccination',
            'record_date' => now()->subMonths(4)->toDateString(),
            'notes' => 'FVRCP and Rabies boosters. Tolerated well.',
        ]);

        // Mochi's records
        MedicalRecord::create([
            'pet_id' => $mochi->id,
            'recorded_by' => $admin->id,
            'record_type' => 'checkup',
            'record_date' => now()->subMonths(2)->toDateString(),
            'notes' => 'Senior wellness exam. Slight dental tartar — recommend dental cleaning within 6 months. Otherwise healthy.',
        ]);
        MedicalRecord::create([
            'pet_id' => $mochi->id,
            'recorded_by' => $admin->id,
            'record_type' => 'vaccination',
            'record_date' => now()->subMonths(2)->subDays(3)->toDateString(),
            'notes' => 'Annual boosters completed. Rabies, DHPP. No issues.',
        ]);

        // Thunder's records
        MedicalRecord::create([
            'pet_id' => $thunder->id,
            'recorded_by' => $admin->id,
            'record_type' => 'checkup',
            'record_date' => now()->subMonths(5)->toDateString(),
            'notes' => 'Intake exam. Neutered male in excellent condition. Previously trained as a working dog.',
        ]);

        // -------------------------------------------------------
        // 4. APPLICATIONS
        // -------------------------------------------------------

        // Lena applied for Mochi — currently at meet & greet stage
        $app1 = Application::create([
            'user_id' => $adopter1->id,
            'pet_id' => $mochi->id,
            'status' => 'meet_greet',
            'notes' => 'I live in a quiet apartment and work from home. I have experience with senior dogs and am looking for a calm companion.',
            'submitted_at' => now()->subDays(8),
        ]);

        // James applied for Biscuit — under review
        $app2 = Application::create([
            'user_id' => $adopter2->id,
            'pet_id' => $biscuit->id,
            'status' => 'under_review',
            'notes' => 'We have a large garden and two kids aged 8 and 10. We have owned Labradors before and are excited to give Biscuit a great home.',
            'submitted_at' => now()->subDays(6),
        ]);

        // Sofia applied for Luna — approved and completed
        $app3 = Application::create([
            'user_id' => $adopter3->id,
            'pet_id' => $luna->id,
            'status' => 'completed',
            'notes' => 'I live alone in a peaceful condo and have always had Siamese cats. I can provide a safe indoor life for Luna.',
            'submitted_at' => now()->subDays(20),
        ]);

        // Marco applied for Thunder — just submitted
        $app4 = Application::create([
            'user_id' => $adopter4->id,
            'pet_id' => $thunder->id,
            'status' => 'pending',
            'notes' => 'I am an active runner and hiker. I have a house with a large yard and 5 years of experience with large breed dogs.',
            'submitted_at' => now()->subDays(2),
        ]);

        // Lena also applied for Pebbles — under review
        $app5 = Application::create([
            'user_id' => $adopter1->id,
            'pet_id' => $pebbles->id,
            'status' => 'under_review',
            'notes' => 'My daughter is mildly allergic to some animals so Pebbles sounds perfect. We have a quiet home and lots of love to give.',
            'submitted_at' => now()->subDays(4),
        ]);

        // -------------------------------------------------------
        // 5. MEET & GREETS
        // -------------------------------------------------------

        // Lena's meet & greet for Mochi — assigned to Carlos
        MeetGreet::create([
            'application_id' => $app1->id,
            'volunteer_id' => $volunteer1->id,
            'scheduled_at' => now()->addDays(2)->setHour(10)->setMinute(0)->setSecond(0),
            'status' => 'scheduled',
            'notes' => 'Please meet at the shelter front desk. Bring a valid ID.',
        ]);

        // Sofia's completed meet & greet for Luna — assigned to Ana
        MeetGreet::create([
            'application_id' => $app3->id,
            'volunteer_id' => $volunteer2->id,
            'scheduled_at' => now()->subDays(14)->setHour(14)->setMinute(0)->setSecond(0),
            'status' => 'completed',
            'notes' => 'Went very well. Luna warmed up to Sofia quickly.',
        ]);
    }
}