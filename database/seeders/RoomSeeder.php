<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Faker\Factory as Faker;
use Symfony\Component\Console\Helper\ProgressBar;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       // Truncate tables
       DB::table('room_reservation')->truncate();
       DB::table('reservations')->truncate();
       DB::table('rooms')->truncate();
       DB::table('room_categories')->truncate();

       $faker = Faker::create();

       // Seed room categories
       $categories = [];
       for ($i = 1; $i <= 10; $i++) {
           $categories[] = [
               'display_name' => "Deluxe Test $i",
               'bed_type' => "Queen Size",
               'near_at' => "Poolside",
               'description' => "A luxurious suite with all amenities.",
               'availability' => true,
               'created_at' => now(),
               'updated_at' => now(),
           ];
       }
       DB::table('room_categories')->insert($categories);

       // Fetch all room category IDs
       $categoryIds = DB::table('room_categories')->pluck('id')->toArray();

       // Seed rooms
       $rooms = [];
       for ($i = 1; $i <= 20; $i++) {
           $rooms[] = [
               'room_category_id' => $categoryIds[array_rand($categoryIds)],
               'name' => "Room " . (100 + $i),
               'price' => rand(200, 500) + rand(0, 99) / 100,
               'pax' => rand(1, 6),
               'availability' => true,
               'created_at' => now(),
               'updated_at' => now(),
           ];
       }
       DB::table('rooms')->insert($rooms);

       // Fetch all room IDs
       $roomData = DB::table('rooms')->get();
       $roomIds = $roomData->pluck('id')->toArray();

       // Seed reservations in chunks
       $totalReservations = 100000;
       $chunkSize = 500; // Insert 500 reservations at a time

       $chunks = ceil($totalReservations / $chunkSize);

       // Set up progress bar
       $progress = new ProgressBar($this->command->getOutput(), $totalReservations);
       $progress->start();

       // Progress tracking
       for ($chunk = 0; $chunk < $chunks; $chunk++) {
           $reservations = [];
           $roomReservations = [];
           $bookedDates = []; // Track bookings by room to prevent overlaps

           for ($i = 0; $i < $chunkSize; $i++) {
               $reservedRooms = $faker->randomElements($roomIds, rand(1, 3)); // Randomly reserve 1-3 rooms
               $checkInDate = $faker->dateTimeBetween('-120 years', 'now')->format('Y-m-d'); // Dates since 1900s
               $checkOutDate = date('Y-m-d', strtotime("+".rand(1, 3)." days", strtotime($checkInDate)));

               foreach ($reservedRooms as $roomId) {
                   // Ensure no overlapping bookings for the same room
                   while (isset($bookedDates[$roomId][$checkInDate])) {
                       $checkInDate = $faker->dateTimeBetween('-120 years', 'now')->format('Y-m-d');
                       $checkOutDate = date('Y-m-d', strtotime("+".rand(1, 3)." days", strtotime($checkInDate)));
                   }
                   $bookedDates[$roomId][$checkInDate] = true;

                   // Populate room_reservation pivot data
                   $roomReservations[] = [
                       'room_id' => $roomId,
                       'reservation_id' => ($chunk * $chunkSize) + $i + 1,
                       'created_at' => now(),
                       'updated_at' => now(),
                   ];
               }

               // Create the reservation
               $reservations[] = [
                   'name' => $faker->name,
                   'email' => $faker->email,
                   'address' => $faker->address,
                   'phone' => $faker->phoneNumber,
                   'nationality' => $faker->randomElement(['Filipino', 'American', 'Australian', 'Japanese', 'Korean']),
                   'type' => $faker->randomElement(['in-house', 'walk-in']),
                   'check_in_date' => $checkInDate,
                   'check_out_date' => $checkOutDate,
                   'room_details' => json_encode(DB::table('rooms')->whereIn('id', $reservedRooms)->get()), // Store room details as JSON
                   'remarks' => $faker->sentence,
                   'created_at' => now(),
                   'updated_at' => now(),
               ];
           }

           // Insert reservations and pivot table data
           DB::table('reservations')->insert($reservations);
           DB::table('room_reservation')->insert($roomReservations);

           // Advance the progress bar
           $progress->advance($chunkSize);
       }

       $progress->finish();

       // Log completion
       info("Seeder completed.");
    }
}
