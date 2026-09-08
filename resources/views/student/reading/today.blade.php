<x-app-layout>
    <div class="space-y-6">
        <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.25em] text-indigo-600">Renungan Hari Ini</p>
                    <h1 class="mt-2 text-3xl font-bold text-slate-900">{{ $reflection->title }}</h1>
                    <p class="mt-2 text-sm text-slate-600">Tanggal: {{ \Carbon\Carbon::parse($reflection->date)->toDateString() }}</p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm">
                    <div class="text-slate-500">Status baca</div>
                    @if($existing?->completed)
                        <div class="mt-1 font-semibold text-green-700">✓ Sudah dibaca</div>
                    @else
                        <div class="mt-1 font-semibold text-blue-700">Sedang dipantau secara diam-diam</div>
                    @endif
                </div>
            </div>
        </section>

        <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="rounded-2xl bg-gradient-to-r from-indigo-50 to-blue-50 p-4">
                <div class="text-sm font-semibold text-indigo-700">Catatan sistem</div>
                <p class="mt-1 text-sm text-slate-600">Durasi membaca akan tercatat otomatis di latar belakang, tanpa menampilkan timer yang mengganggu fokus Anda.</p>
            </div>

            <div class="mt-5 rounded-2xl border border-slate-200 p-5">
                <div class="flex flex-wrap items-center gap-3">
                    <div class="text-sm font-semibold text-slate-700">Ayat</div>
                    <div class="text-sm text-slate-600">{{ $reflection->bible_verse ?? '-' }}</div>
                </div>

                <div class="prose prose-slate mt-5 max-w-none text-base leading-8">
                    {!! nl2br(e($reflection->content)) !!}
                </div>
            </div>
        </section>

        <form method="POST" action="{{ route('student.reading.today.store') }}" id="readingForm" style="display: none;">
            @csrf
            <input type="hidden" name="reflection_id" value="{{ $reflection->id }}">
            <input type="hidden" name="read_at" id="readAt" value="">
            <input type="hidden" name="duration_seconds" id="durationSeconds" value="0">
            <input type="hidden" name="completed" id="completedField" value="1">
        </form>

        <script>
            (function() {
                const pageStartTime = Date.now();
                const reflectionId = {{ $reflection->id }};
                const form = document.getElementById('readingForm');
                const durationSecondsInput = document.getElementById('durationSeconds');
                const readAtInput = document.getElementById('readAt');

                readAtInput.value = new Date().toISOString();

                function submitReadingDuration() {
                    const durationSeconds = Math.floor((Date.now() - pageStartTime) / 1000);
                    durationSecondsInput.value = durationSeconds;

                    const formData = new FormData(form);
                    fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    }).catch(err => console.log('Reading recorded'));
                }

                window.addEventListener('beforeunload', function() {
                    const durationSeconds = Math.floor((Date.now() - pageStartTime) / 1000);
                    durationSecondsInput.value = durationSeconds;

                    const formData = new FormData(form);
                    const params = new URLSearchParams(formData);
                    navigator.sendBeacon(form.action, params);
                });

                setInterval(function() {
                    const durationSeconds = Math.floor((Date.now() - pageStartTime) / 1000);
                    durationSecondsInput.value = durationSeconds;

                    const formData = new FormData(form);
                    fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    }).catch(err => console.log('Periodic reading update'));
                }, 30000);

                console.log('Reading tracker started for reflection ID:', reflectionId);
            })();
        </script>
    </div>
</x-app-layout>

