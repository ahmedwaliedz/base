# Plan 03 — Seeder Performance (Bulk Insert)

## Problem

Current seeders fire Eloquent model events and execute **one INSERT per row**:

| Seeder | Current approach | Issue |
|--------|-----------------|-------|
| `UserSeeder` | `User::factory()->count(10)->create()` | One query + model events per user |
| `ComplaintSeeder` | `->each(fn($c) => ...)` over 100 complaints × 3 images × 3 replays | ~700 queries for 100 complaints |
| `ContactMessageSeeder` | Likely similar pattern | N queries |

At scale (500+ records), this causes timeouts and excessive memory usage.

---

## Target Approach

Use `DB::table()->insert()` with chunked arrays — skips Eloquent model events, timestamps handled manually, single query per chunk.

**Rule:** chunk size = 500 rows max per insert to avoid `max_allowed_packet` limits.

---

## Files to Change

| File | Change |
|------|--------|
| `database/seeders/User/UserSeeder.php` | Rewrite with bulk insert |
| `database/seeders/Complaint/ComplaintSeeder.php` | Rewrite with bulk insert + related tables |
| `database/seeders/Complaint/ContactMessageSeeder.php` | Rewrite with bulk insert |

---

## Implementation

### UserSeeder — Bulk Insert Pattern

```php
public function run(): void
{
    $count = 200;
    $chunk = 500;
    $now   = now();

    $rows = [];
    for ($i = 1; $i <= $count; $i++) {
        $rows[] = [
            'name'         => fake()->name(),
            'email'        => fake()->unique()->safeEmail(),
            'phone'        => fake()->numerify('05########'),
            'country_code' => '966',
            'password'     => bcrypt('password'),
            'is_active'    => true,
            'is_blocked'   => false,
            'is_notify'    => true,
            'is_complete_info' => true,
            'image'        => 'default.png',
            'created_at'   => $now,
            'updated_at'   => $now,
        ];
    }

    foreach (array_chunk($rows, $chunk) as $batch) {
        DB::table('users')->insert($batch);
    }
}
```

### ComplaintSeeder — Bulk Insert with Related Tables

```php
public function run(): void
{
    $complaintCount = 200;
    $imagesPerComplaint = 3;
    $replaysPerComplaint = 2;
    $chunk = 500;
    $now = now();

    // Step 1: bulk insert complaints
    $complaintRows = [];
    for ($i = 0; $i < $complaintCount; $i++) {
        $complaintRows[] = [
            'name'       => fake()->name(),
            'phone'      => fake()->phoneNumber(),
            'email'      => fake()->safeEmail(),
            'subject'    => fake()->sentence(4),
            'complaint'  => fake()->paragraph(),
            'type'       => fake()->randomElement(['general', 'technical']),
            'status'     => fake()->randomElement(['pending', 'processing', 'completed', 'rejected']),
            'created_at' => $now,
            'updated_at' => $now,
        ];
    }

    foreach (array_chunk($complaintRows, $chunk) as $batch) {
        DB::table('complaints')->insert($batch);
    }

    // Step 2: fetch inserted IDs in one query
    $complaintIds = DB::table('complaints')
        ->latest('id')
        ->limit($complaintCount)
        ->pluck('id');

    // Step 3: bulk insert images
    $imageRows = [];
    foreach ($complaintIds as $complaintId) {
        for ($j = 0; $j < $imagesPerComplaint; $j++) {
            $imageRows[] = [
                'complaint_id' => $complaintId,
                'image'        => 'complaints/placeholder.jpg',
                'created_at'   => $now,
                'updated_at'   => $now,
            ];
        }
    }
    foreach (array_chunk($imageRows, $chunk) as $batch) {
        DB::table('complaint_images')->insert($batch);
    }

    // Step 4: bulk insert replays
    $replayRows = [];
    foreach ($complaintIds as $complaintId) {
        for ($j = 0; $j < $replaysPerComplaint; $j++) {
            $replayRows[] = [
                'replayable_id'   => $complaintId,
                'replayable_type' => \App\Models\Complaint::class,
                'replay'          => fake()->sentence(),
                'created_at'      => $now,
                'updated_at'      => $now,
            ];
        }
    }
    foreach (array_chunk($replayRows, $chunk) as $batch) {
        DB::table('replays')->insert($batch);
    }
}
```

### ContactMessageSeeder — Bulk Insert

```php
public function run(): void
{
    $count = 100;
    $chunk = 500;
    $now   = now();

    $rows = [];
    for ($i = 0; $i < $count; $i++) {
        $rows[] = [
            'name'       => fake()->name(),
            'email'      => fake()->safeEmail(),
            'phone'      => fake()->phoneNumber(),
            'message'    => fake()->paragraph(),
            'created_at' => $now,
            'updated_at' => $now,
        ];
    }

    foreach (array_chunk($rows, $chunk) as $batch) {
        DB::table('contact_messages')->insert($batch);
    }
}
```

---

## General Rules for All Future Seeders

- Always use `DB::table('...')->insert($batch)` for > 20 rows
- Always chunk at 500 rows max
- Never use `->each()` for creating related records — batch them separately
- Use a single `$now = now()` shared across all rows in one seeder run
- Use `fake()` (Laravel 9+ global helper) instead of `$this->faker`
- Use `DB::table()->latest('id')->limit($n)->pluck('id')` to get inserted IDs without loading Eloquent models

---

## Expected Performance Improvement

| Seeder | Before | After |
|--------|--------|-------|
| 200 users | ~200 queries | 1 query |
| 200 complaints + 600 images + 400 replays | ~1200 queries | 3 queries |

---

## Acceptance Criteria

- [ ] `php artisan db:seed --class=UserSeeder` runs in < 2s for 200 records
- [ ] `php artisan db:seed --class=ComplaintSeeder` runs in < 3s for 200 complaints
- [ ] No `N+1` pattern inside any seeder `each()` loop
- [ ] Chunk size never exceeds 500 rows per insert
