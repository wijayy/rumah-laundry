<div>
    <div class="grid grid-cols-2 gap-4">
        <div class="aspect-16/7 bg-neutral-100 dark:bg-neutral-600 p-2 rounded-lg">
            <div class="lg:text-xl">Pendapatan</div>
            <div class="flex mt-2 justify-between">
                <div class="text-lg text-mine-300">Rp. {{ number_format($pendapatan / 1000, 0, ',', '.') }}K</div>
                @if ($pendapatanDiff >= 0)
                    <div class="text-sm lg:text-base flex p-1 rounded bg-green-100 dark:bg-green-700 items-center">
                        <flux:icon.arrow-trending-up variant="mini"></flux:icon.arrow-trending-up>{{ $pendapatanDiff }}%
                    </div>
                @else
                    <div class="text-sm lg:text-base flex p-1 rounded bg-rose-100 dark:bg-rose-700 items-center">
                        <flux:icon.arrow-trending-down variant="mini"></flux:icon.arrow-trending-down>{{ $pendapatanDiff }}%
                    </div>
                @endif
            </div>
        </div>
        <div class="aspect-16/7 bg-neutral-100 dark:bg-neutral-600 p-2 rounded-lg">
            <div class="lg:text-xl">Pendapatan</div>
            <div class="flex mt-2 justify-between">
                <div class="text-xl text-mine-300">{{ number_format($transaksi, 0, ',', '.') }}</div>
                @if ($transaksiDiff >= 0)
                    <div class="text-sm lg:text-base flex p-1 rounded bg-green-100 dark:bg-green-700 items-center">
                        <flux:icon.arrow-trending-up variant="mini"></flux:icon.arrow-trending-up>{{ $transaksiDiff }}%
                    </div>
                @else
                    <div class="text-sm lg:text-base flex p-1 rounded bg-rose-100 dark:bg-rose-700 items-center">
                        <flux:icon.arrow-trending-down variant="mini"></flux:icon.arrow-trending-down>{{ $transaksiDiff }}%
                    </div>
                @endif
            </div>
        </div>
    </div>

    <canvas id="grafikTotal" class="aspect-square max-h-72 lg:max-h-96 mt-4"></canvas>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- SUM(jumlah) Pie -->
        <div>
            <canvas id="sumPieChart" height="400"></canvas>
        </div>

        <!-- COUNT(service_id) Pie -->
        <div>
            <canvas id="countPieChart" height="400"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>

    const ctx = document.getElementById('grafikTotal').getContext('2d');

    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($labels),
            datasets: [{
                label: 'Total Transaksi',
                data: @json($totals),
                fill: true,
                backgroundColor: 'rgba(59, 130, 246, 0.1)', // Tailwind blue-500 with opacity
                borderColor: 'rgba(59, 130, 246, 1)',
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false, // NONAKTIFKAN default
            // aspectRatio: 1,             // SET aspect ratio 1:1
            plugins: {
                title: {
                    display: true,
                    text: 'Pertumbuhan Pendapatan', // ← Judul grafik di sini
                    font: {
                        size: 20,
                        weight: 'bold'
                    },
                    padding: {
                        top: 10,
                        bottom: 30
                    }
                },
                legend: { display: true },
                tooltip: { mode: 'index', intersect: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function (value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });

    const labels = @json($pielabels);
    const sumValues = @json($sumValues);
    const sumSatuan = @json($sumSatuan);
    const countValues = @json($countValues);
    const colors = ['#3B82F6', '#14B8A6', '#F97316', '#EC4899', '#8B5CF6'];

    // Pie Chart: SUM(jumlah)
    new Chart(document.getElementById('sumPieChart').getContext('2d'), {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                data: sumValues,
                backgroundColor: colors,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Total Unit per Service',
                    font: { size: 16 }
                },
                legend: { position: 'bottom' },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            const index = context.dataIndex;
                            const nama = context.label;
                            const jumlah = context.parsed;
                            const satuan = sumSatuan[index] || '';
                            return `${nama}: ${jumlah} ${satuan}`;
                        }
                    }
                }
            }
        }
    });

    // Pie Chart: COUNT(service_id)
    new Chart(document.getElementById('countPieChart').getContext('2d'), {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                data: countValues,
                backgroundColor: colors,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Frekuensi Transaksi per Service',
                    font: { size: 16 }
                },
                legend: { position: 'bottom' },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            const nama = context.label;
                            const value = context.parsed;
                            return `${nama}: ${value}x digunakan`;
                        }
                    }
                }

            }
        }
    });


</script>
