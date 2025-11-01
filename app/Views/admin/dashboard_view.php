<div class="container mx-auto px-4 py-8">
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-white mb-2">Dashboard Admin</h1>
        <p class="text-gray-400">Selamat datang, <?= session()->get('adminName') ?>!</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Card 1: Booking Menunggu Validasi -->
        <div class="bg-gray-800 rounded-lg p-6 shadow-lg border border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-white mb-2">Booking Menunggu Validasi</h2>
                    <p class="text-3xl font-bold text-yellow-400"><?= $ready_to_validate_count ?></p>
                </div>
                <div class="text-yellow-400">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <a href="<?= base_url('admin/bookings') ?>" class="mt-4 inline-block text-yellow-400 hover:text-white font-semibold">Lihat Detail →</a>
        </div>

        <!-- Card 2: Booking Siap Aktivasi Garansi -->
        <div class="bg-gray-800 rounded-lg p-6 shadow-lg border border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-white mb-2">Booking Siap Aktivasi Garansi</h2>
                    <p class="text-3xl font-bold text-green-400"><?= $ready_to_complete_count ?></p>
                </div>
                <div class="text-green-400">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
            <a href="<?= base_url('admin/completed-bookings') ?>" class="mt-4 inline-block text-green-400 hover:text-white font-semibold">Lihat Detail →</a>
        </div>
    </div>

    <div class="text-center">
        <a href="<?= base_url('admin/logout') ?>" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">Logout</a>
    </div>
</div>
