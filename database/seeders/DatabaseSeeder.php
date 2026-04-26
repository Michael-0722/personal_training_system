<?php

namespace Database\Seeders;

use App\Models\Availability;
use App\Models\Booking;
use App\Models\ClientProfile;
use App\Models\Review;
use App\Models\SessionType;
use App\Models\TrainerProfile;
use App\Models\TrainifyNotification;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->resetDemoData();

        User::query()->updateOrCreate(
            ['username' => 'admin'],
            [
                'username' => 'admin',
                'full_name' => 'Trainify Admin',
                'password' => Hash::make('Admin@12345'),
                'role' => 'admin',
                'account_status' => 'active',
            ]
        );

        $trainers = User::factory()
            ->count(30)
            ->create([
                'role' => 'trainer',
                'account_status' => 'active',
            ]);

        $clients = User::factory()
            ->count(70)
            ->create([
                'role' => 'client',
                'account_status' => 'active',
            ]);

        $trainerProfiles = $this->seedTrainerProfiles($trainers);
        $this->seedClientProfiles($clients);

        $sessionTypes = $this->seedSessionTypes($trainerProfiles);
        $bookings = $this->seedBookings($clients, $sessionTypes, 180);

        $this->seedTransactionsAndReviews($bookings);
        $this->recalculateProfileStats();
        $this->seedNotifications($trainers, $clients);
    }

    private function resetDemoData(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        TrainifyNotification::truncate();
        Review::truncate();
        Transaction::truncate();
        Booking::truncate();
        Availability::truncate();
        SessionType::truncate();
        TrainerProfile::truncate();
        ClientProfile::truncate();
        User::truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    private function seedTrainerProfiles(Collection $trainers): Collection
    {
        $specializationPool = [
            'Weight Loss',
            'Strength Training',
            'Mobility',
            'HIIT',
            'Bodybuilding',
            'Nutrition',
            'Yoga',
            'Rehab',
            'Sports Conditioning',
            'Functional Fitness',
        ];

        return $trainers->map(function (User $trainer) use ($specializationPool) {
            $specializations = collect($specializationPool)
                ->shuffle()
                ->take(fake()->numberBetween(2, 4))
                ->values()
                ->all();

            $profile = TrainerProfile::create([
                'user_id' => $trainer->id,
                'bio' => fake()->paragraph(),
                'specializations' => $specializations,
                'tags' => collect($specializations)->map(fn (string $item) => strtolower(str_replace(' ', '-', $item)))->all(),
                'rating' => fake()->randomFloat(2, 3.8, 5),
                'review_count' => fake()->numberBetween(0, 80),
                'approval_status' => fake()->randomElement(['approved', 'approved', 'approved', 'pending', 'rejected']),
                'sessions_completed' => fake()->numberBetween(0, 200),
                'total_earnings' => fake()->randomFloat(2, 0, 25000),
                'hourly_rate' => fake()->randomFloat(2, 25, 180),
                'rejection_reason' => null,
            ]);

            if ($profile->approval_status === 'rejected') {
                $profile->update(['rejection_reason' => fake()->sentence()]);
            }

            $dayCount = fake()->numberBetween(3, 6);
            foreach (collect(range(0, 6))->shuffle()->take($dayCount) as $dayOfWeek) {
                Availability::create([
                    'trainer_profile_id' => $profile->id,
                    'day_of_week' => $dayOfWeek,
                    'start_time' => fake()->randomElement(['06:00:00', '07:00:00', '08:00:00', '09:00:00']),
                    'end_time' => fake()->randomElement(['16:00:00', '17:00:00', '18:00:00', '19:00:00', '20:00:00']),
                ]);
            }

            return $profile;
        });
    }

    private function seedClientProfiles(Collection $clients): void
    {
        foreach ($clients as $client) {
            ClientProfile::create([
                'user_id' => $client->id,
                'total_spent' => 0,
            ]);
        }
    }

    private function seedSessionTypes(Collection $trainerProfiles): Collection
    {
        $titles = [
            'Beginner Fat Loss Program',
            '1-on-1 Strength Session',
            'Muscle Gain Blueprint',
            'Core and Stability Class',
            'Mobility Reset',
            'Conditioning Blast',
            'Online Technique Review',
            'Weekly Performance Coaching',
        ];

        $sessionTypes = collect();

        foreach ($trainerProfiles as $profile) {
            $count = fake()->numberBetween(2, 4);

            for ($i = 0; $i < $count; $i++) {
                $format = fake()->randomElement(['1-on-1', 'Group']);

                $sessionTypes->push(SessionType::create([
                    'trainer_profile_id' => $profile->id,
                    'title' => fake()->randomElement($titles),
                    'description' => fake()->sentence(12),
                    'format' => $format,
                    'delivery_mode' => fake()->randomElement(['Online', 'In-Person']),
                    'duration_minutes' => fake()->randomElement([30, 45, 60, 75, 90]),
                    'price' => fake()->randomFloat(2, 20, 220),
                    'max_participants' => $format === 'Group' ? fake()->numberBetween(3, 20) : null,
                    'is_active' => fake()->boolean(85),
                ]));
            }
        }

        return $sessionTypes;
    }

    private function seedBookings(Collection $clients, Collection $sessionTypes, int $count): Collection
    {
        $bookings = collect();

        $sessionTypes = SessionType::query()
            ->with('trainerProfile.user')
            ->whereIn('id', $sessionTypes->pluck('id'))
            ->get();

        for ($i = 0; $i < $count; $i++) {
            /** @var SessionType $sessionType */
            $sessionType = $sessionTypes->random();
            /** @var User $trainer */
            $trainer = $sessionType->trainerProfile->user;

            /** @var User $client */
            $client = $clients->where('id', '!=', $trainer->id)->random();

            $status = fake()->randomElement(['pending', 'confirmed', 'completed', 'cancelled']);

            $bookings->push(Booking::create([
                'client_id' => $client->id,
                'trainer_id' => $trainer->id,
                'session_type_id' => $sessionType->id,
                'booking_date' => fake()->dateTimeBetween('-4 months', '+2 months')->format('Y-m-d'),
                'booking_time' => fake()->randomElement(['06:00:00', '07:30:00', '09:00:00', '11:00:00', '14:00:00', '17:30:00']),
                'duration_minutes' => $sessionType->duration_minutes,
                'amount' => $sessionType->price,
                'status' => $status,
                'cancellation_reason' => $status === 'cancelled' ? fake()->sentence() : null,
            ]));
        }

        return $bookings;
    }

    private function seedTransactionsAndReviews(Collection $bookings): void
    {
        foreach ($bookings as $booking) {
            if (in_array($booking->status, ['confirmed', 'completed'], true)) {
                $gross = (float) $booking->amount;
                $rate = 0.20;
                $commission = round($gross * $rate, 2);

                Transaction::create([
                    'booking_id' => $booking->id,
                    'trainer_id' => $booking->trainer_id,
                    'gross_amount' => $gross,
                    'commission_rate' => $rate,
                    'commission_amount' => $commission,
                    'trainer_payout' => round($gross - $commission, 2),
                    'payout_status' => fake()->randomElement(['pending', 'paid']),
                    'paid_at' => fake()->boolean(50) ? fake()->dateTimeBetween('-3 months', 'now') : null,
                ]);
            }

            if ($booking->status === 'completed' && fake()->boolean(75)) {
                Review::create([
                    'booking_id' => $booking->id,
                    'client_id' => $booking->client_id,
                    'trainer_id' => $booking->trainer_id,
                    'rating' => fake()->numberBetween(3, 5),
                    'comment' => fake()->sentence(),
                ]);
            }
        }
    }

    private function recalculateProfileStats(): void
    {
        TrainerProfile::query()->with('user')->each(function (TrainerProfile $profile) {
            $trainerId = $profile->user_id;

            $completedCount = Booking::query()
                ->where('trainer_id', $trainerId)
                ->where('status', 'completed')
                ->count();

            $earnings = Transaction::query()
                ->where('trainer_id', $trainerId)
                ->sum('trainer_payout');

            $reviews = Review::query()
                ->where('trainer_id', $trainerId)
                ->get(['rating']);

            $profile->update([
                'sessions_completed' => $completedCount,
                'total_earnings' => round((float) $earnings, 2),
                'review_count' => $reviews->count(),
                'rating' => $reviews->count() > 0
                    ? round((float) $reviews->avg('rating'), 2)
                    : 0,
            ]);
        });

        ClientProfile::query()->each(function (ClientProfile $profile) {
            $total = Booking::query()
                ->where('client_id', $profile->user_id)
                ->whereIn('status', ['confirmed', 'completed'])
                ->sum('amount');

            $profile->update([
                'total_spent' => round((float) $total, 2),
            ]);
        });
    }

    private function seedNotifications(Collection $trainers, Collection $clients): void
    {
        $users = $trainers->merge($clients)->shuffle()->take(80);

        foreach ($users as $user) {
            TrainifyNotification::create([
                'user_id' => $user->id,
                'type' => fake()->randomElement([
                    'booking_confirmed',
                    'booking_cancelled',
                    'review_received',
                    'payout_processed',
                ]),
                'title' => fake()->sentence(4),
                'message' => fake()->sentence(12),
                'is_read' => fake()->boolean(40),
                'data' => [
                    'source' => 'seeder',
                    'created_for_demo' => true,
                ],
            ]);
        }
    }
}
