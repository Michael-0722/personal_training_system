<?php

use App\Models\Booking;
use App\Models\SessionType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('prevents booking when a group slot is already full', function () {
    /** @var User $trainer */
    $trainer = User::factory()->create(['role' => 'trainer']);
    $trainerProfile = $trainer->trainerProfile()->create();

    /** @var User $clientOne */
    $clientOne = User::factory()->create(['role' => 'client']);
    /** @var User $clientTwo */
    $clientTwo = User::factory()->create(['role' => 'client']);
    /** @var User $clientThree */
    $clientThree = User::factory()->create(['role' => 'client']);

    $bookingDate = Carbon::tomorrow();

    $trainerProfile->availabilities()->create([
        'day_of_week' => $bookingDate->dayOfWeek,
        'start_time' => '08:00:00',
        'end_time' => '18:00:00',
    ]);

    $session = SessionType::query()->create([
        'trainer_profile_id' => $trainerProfile->id,
        'title' => 'Group HIIT',
        'description' => 'Group conditioning',
        'format' => 'Group',
        'delivery_mode' => 'Online',
        'duration_minutes' => 60,
        'price' => 35,
        'max_participants' => 2,
        'is_active' => true,
    ]);

    Booking::query()->create([
        'client_id' => $clientOne->id,
        'trainer_id' => $trainer->id,
        'session_type_id' => $session->id,
        'booking_date' => $bookingDate->toDateString(),
        'booking_time' => '10:00:00',
        'duration_minutes' => 60,
        'amount' => 35,
        'status' => 'confirmed',
    ]);

    Booking::query()->create([
        'client_id' => $clientTwo->id,
        'trainer_id' => $trainer->id,
        'session_type_id' => $session->id,
        'booking_date' => $bookingDate->toDateString(),
        'booking_time' => '10:00:00',
        'duration_minutes' => 60,
        'amount' => 35,
        'status' => 'pending',
    ]);

    $response = $this->actingAs($clientThree)->post(route('client.book.store', [$trainer, $session]), [
        'booking_date' => $bookingDate->toDateString(),
        'booking_time' => '10:00',
    ]);

    $response->assertSessionHasErrors('booking_time');

    expect(Booking::query()
        ->where('trainer_id', $trainer->id)
        ->where('session_type_id', $session->id)
        ->where('booking_date', $bookingDate->toDateString())
        ->where('booking_time', '10:00:00')
        ->count())->toBe(2);
});

it('allows booking the same group slot while capacity remains', function () {
    /** @var User $trainer */
    $trainer = User::factory()->create(['role' => 'trainer']);
    $trainerProfile = $trainer->trainerProfile()->create();

    /** @var User $clientOne */
    $clientOne = User::factory()->create(['role' => 'client']);
    /** @var User $clientTwo */
    $clientTwo = User::factory()->create(['role' => 'client']);

    $bookingDate = Carbon::tomorrow();

    $trainerProfile->availabilities()->create([
        'day_of_week' => $bookingDate->dayOfWeek,
        'start_time' => '08:00:00',
        'end_time' => '18:00:00',
    ]);

    $session = SessionType::query()->create([
        'trainer_profile_id' => $trainerProfile->id,
        'title' => 'Group Mobility',
        'description' => 'Group flexibility session',
        'format' => 'Group',
        'delivery_mode' => 'Online',
        'duration_minutes' => 60,
        'price' => 40,
        'max_participants' => 2,
        'is_active' => true,
    ]);

    Booking::query()->create([
        'client_id' => $clientOne->id,
        'trainer_id' => $trainer->id,
        'session_type_id' => $session->id,
        'booking_date' => $bookingDate->toDateString(),
        'booking_time' => '11:00:00',
        'duration_minutes' => 60,
        'amount' => 40,
        'status' => 'confirmed',
    ]);

    $response = $this->actingAs($clientTwo)->post(route('client.book.store', [$trainer, $session]), [
        'booking_date' => $bookingDate->toDateString(),
        'booking_time' => '11:00',
    ]);

    $response->assertRedirect(route('client.bookings.index'));

    expect(Booking::query()
        ->where('trainer_id', $trainer->id)
        ->where('session_type_id', $session->id)
        ->where('booking_date', $bookingDate->toDateString())
        ->where('booking_time', '11:00:00')
        ->count())->toBe(2);
});
