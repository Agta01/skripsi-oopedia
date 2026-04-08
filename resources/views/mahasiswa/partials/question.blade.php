@php
    use App\Models\Progress;
@endphp

<div class="materi-card shadow-sm rounded">
    <div class="materi-card-body p-4">
        <div id="questionContainer">
            <form id="questionForm" class="ajax-form"
                action="{{ route('questions.check-answer', [
                    'material' => $material->id,
                    'question' => $currentQuestion->id,
                    'difficulty' => $difficulty,
                ]) }}"
                method="POST">
                @csrf
                <input type="hidden" name="question_id" value="{{ $currentQuestion->id }}">
                <input type="hidden" name="material_id" value="{{ $material->id }}">
                <input type="hidden" name="difficulty" value="{{ $difficulty }}">

                <div class="question-header mb-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="badge bg-gradient-primary p-2 px-3">
                            <i class="fas fa-question-circle me-2"></i>
                            @php
                                $difficulty = request()->query('difficulty', 'all');
                                $isGuest = !auth()->check() || (auth()->check() && auth()->user()->role_id === 4);

                                // Hitung jumlah soal berdasarkan konfigurasi
                                if ($difficulty !== 'all') {
                                    $difficultyQuestions = $material->questions->where('difficulty', $difficulty);

                                    // Ambil konfigurasi
                                    if ($isGuest) {
                                        // Untuk guest, maksimal 3 soal
                                        $configuredTotal = min(3, $difficultyQuestions->count());
                                    } else {
                                        // Untuk pengguna terdaftar, gunakan konfigurasi dari admin
                                        $config = App\Models\QuestionBankConfig::where('material_id', $material->id)
                                            ->where('is_active', true)
                                            ->first();

                                        if ($config) {
                                            $countField = $difficulty . '_count';
                                            $configuredTotal = $config->$countField;
                                        } else {
                                            $configuredTotal = $difficultyQuestions->count();
                                        }
                                    }
                                } else {
                                    // Jika all difficulty, hitung total dari semua tingkat kesulitan
                                    if ($isGuest) {
                                        // Untuk guest, maksimal 3 soal per tingkat
                                        $beginnerCount = min(
                                            3,
                                            $material->questions->where('difficulty', 'beginner')->count(),
                                        );
                                        $mediumCount = min(
                                            3,
                                            $material->questions->where('difficulty', 'medium')->count(),
                                        );
                                        $hardCount = min(3, $material->questions->where('difficulty', 'hard')->count());
                                        $configuredTotal = $beginnerCount + $mediumCount + $hardCount;
                                    } else {
                                        // Untuk pengguna terdaftar, gunakan konfigurasi dari admin
                                        $config = App\Models\QuestionBankConfig::where('material_id', $material->id)
                                            ->where('is_active', true)
                                            ->first();

                                        if ($config) {
                                            $configuredTotal =
                                                $config->beginner_count + $config->medium_count + $config->hard_count;
                                        } else {
                                            $configuredTotal = $material->questions->count();
                                        }
                                    }
                                }

                                // Calculate the current question number
                                $answeredInDifficulty = App\Models\Progress::where(
                                    'user_id',
                                    auth()->id() ?? session()->getId(),
                                )
                                    ->where('material_id', $material->id)
                                    ->where('is_correct', true);

                                if ($difficulty !== 'all') {
                                    $answeredInDifficulty = $answeredInDifficulty->whereIn(
                                        'question_id',
                                        $difficultyQuestions->pluck('id'),
                                    );
                                }

                                $answeredCount = $answeredInDifficulty->count();
                                $currentNumberInDifficulty = $answeredCount + 1;

                                if ($currentNumberInDifficulty > $configuredTotal) {
                                    $currentNumberInDifficulty = $configuredTotal;
                                }
                            @endphp
                            Soal {{ $currentNumberInDifficulty }} dari {{ $configuredTotal }}
                        </span>
                        <span
                            class="badge bg-{{ $currentQuestion->difficulty == 'beginner' ? 'success' : ($currentQuestion->difficulty == 'medium' ? 'warning' : 'danger') }} p-2 px-3">
                            {{ ucfirst($currentQuestion->difficulty) }}
                        </span>
                    </div>
                </div>

                <div class="answers-container">
                    <!-- Tampilkan input teks jika tipe soal adalah fill_in_the_blank -->
                    @if ($currentQuestion->question_type === 'fill_in_the_blank')
                        <div class="question-content mb-4">
                            <h5 class="mb-3"><i class="fas fa-question me-2"></i>Pertanyaan</h5>
                            <div class="question-text whitespace-pre-wrap">
                                {!! $currentQuestion->question_text !!}
                            </div>
                        </div>
                        <div class="fill-in-blank-container p-3 mb-3 rounded">
                            <label for="fill_in_the_blank_answer" class="form-label">Jawaban Anda:</label>
                            <input type="text" name="fill_in_the_blank_answer" id="fill_in_the_blank_answer"
                                class="form-control" placeholder="Ketik jawaban Anda di sini..." required>
                        </div>
                    @else
                        @if ($currentQuestion->question_type === 'drag_and_drop')
                            <div class="question-content">
                                <h5 class=""><i class="fas fa-question me-2"></i>Pertanyaan</h5>
                                <div class="question-html"
                                    style="font-family: monospace; background: #f8f9fa; padding: 10px; border-radius: 5px;">
                                    @php
                                        // Hilangkan tag <p> dan </p> terlebih dahulu
                                        $rawText = str_replace(['<p>', '</p>'], ["", "\n"], $currentQuestion->question_text);

                                        // Hilangkan tag <br>
                                        $finalText = str_replace('<br>', "\n", $rawText);

                                        // Escape karakter < dan >
                                        $escapedText = str_replace(['<', '>'], ['&lt;', '&gt;'], $finalText);

                                        // Ganti [zone] dengan drop-zone span
                                        $zoneCount = substr_count($escapedText, '[zone]');
                                        for ($i = 1; $i <= $zoneCount; $i++) {
                                            $escapedText = preg_replace(
                                                '/\[zone\]/',
                                                '<span class="drop-zone" id="dropZone' .
                                                    $i .
                                                    '" data-zone="' .
                                                    $i .
                                                    '" data-user-answer=""></span>',
                                                $escapedText,
                                                1,
                                            );
                                        }
                                    @endphp
                                    <pre>{!! $escapedText !!}</pre>
                                </div>
                                {{-- Drag and Drop --}}

                                <input type="hidden" name="drag_and_drop_answers" id="dragAndDropAnswers">

                                <h5 class="mt-4 mb-3"><i class="fas fa-list-ul me-2"></i>Pilihan Jawaban</h5>
                                <div class="drag-items d-flex flex-wrap gap-2 mt-2">
                                    @foreach ($currentQuestion->answers as $answer)
                                        <div class="draggable btn btn-outline-primary" draggable="true"
                                            data-value="{{ $answer->answer_text }}">
                                            {{ $answer->answer_text }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="question-content mb-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="tw-bg-indigo-100 tw-text-indigo-600 tw-p-2 tw-rounded-lg shadow-sm me-3">
                                        <i class="fas fa-question fs-5"></i>
                                    </div>
                                    <h5 class="mb-0 fw-bold tw-text-gray-800">Pertanyaan</h5>
                                </div>
                                <div class="question-text whitespace-pre-wrap tw-bg-gradient-to-br tw-from-indigo-50 tw-to-blue-50 tw-border tw-border-indigo-100 tw-rounded-2xl tw-p-5 tw-shadow-sm tw-text-gray-800 tw-leading-relaxed">
                                    {!! $currentQuestion->question_text !!}
                                </div>
                            </div>
                            <!-- Tampilkan radio button bergaya SaaS Card Vibrant -->
                            @foreach ($currentQuestion->answers as $index => $answer)
                                @php
                                    $bgColors = ['tw-bg-blue-50/80', 'tw-bg-green-50/80', 'tw-bg-purple-50/80', 'tw-bg-rose-50/80'];
                                    $borderColors = ['tw-border-blue-200', 'tw-border-green-200', 'tw-border-purple-200', 'tw-border-rose-200'];
                                    $hoverColors = ['hover:tw-bg-blue-100 hover:tw-border-blue-400', 'hover:tw-bg-green-100 hover:tw-border-green-400', 'hover:tw-bg-purple-100 hover:tw-border-purple-400', 'hover:tw-bg-rose-100 hover:tw-border-rose-400'];
                                    $idx = $loop->index % 4;
                                @endphp
                                <label for="answer{{ $answer->id }}" class="tw-block tw-cursor-pointer tw-mb-3 answer-label-wrapper">
                                    <div class="tw-p-4 tw-rounded-2xl tw-border {{ $borderColors[$idx] }} {{ $bgColors[$idx] }} {{ $hoverColors[$idx] }} hover:tw-shadow-md tw-transition-all d-flex align-items-center" data-base-classes="{{ $borderColors[$idx] }} {{ $bgColors[$idx] }}">
                                        <input type="radio" name="answer" id="answer{{ $answer->id }}"
                                            value="{{ $answer->id }}" class="tw-w-5 tw-h-5 tw-text-indigo-600 focus:tw-ring-indigo-500 tw-border-gray-300 me-3" required
                                            onclick="document.querySelectorAll('.answer-label-wrapper div').forEach(div => { div.className = 'tw-p-4 tw-rounded-2xl tw-border hover:tw-shadow-md tw-transition-all d-flex align-items-center ' + div.getAttribute('data-base-classes'); }); this.parentElement.className = 'tw-p-4 tw-rounded-2xl tw-border tw-transition-all d-flex align-items-center tw-bg-indigo-100 tw-border-indigo-500 tw-shadow-md tw-ring-2 tw-ring-indigo-300';">
                                        <div class="mb-0 w-100 tw-text-gray-800 fw-medium tw-leading-relaxed" style="font-size: 15px;">{!! $answer->answer_text !!}</div>
                                    </div>
                                </label>
                            @endforeach
                        @endif
                    @endif
                </div>

                <div class="d-grid">
                    <button type="submit" id="checkAnswerBtn" class="btn btn-primary py-2">
                        <i class="fas fa-check-circle me-2"></i>Periksa Jawaban
                    </button>
                </div>
            </form>
        </div>

        <!-- Feedback UI removed, handled by SweetAlert2 -->
</div>

<style>
    .drop-zone {
        min-width: 130px;
        min-height: 40px;
        border: 2px dashed #6c757d;
        background: linear-gradient(145deg, #f1f3f5, #e9ecef);
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 8px;
        margin: 4px;
        vertical-align: middle;
        text-align: center;
        transition: all 0.3s ease;
        font-weight: 500;
        color: #333;
    }

    .drop-zone:hover {
        background: linear-gradient(145deg, #e2e6ea, #dee2e6);
        border-color: #007bff;
        box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.3);
    }

    .draggable {
        cursor: grab;
        user-select: none;
        background-color: #ffffff;
        border: 1px solid #007bff;
        border-radius: 6px;
        padding: 8px 12px;
        font-size: 14px;
        font-weight: 500;
        color: #007bff;
        box-shadow: 0 2px 4px rgba(0, 123, 255, 0.1);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .draggable:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 8px rgba(0, 123, 255, 0.2);
    }

    .draggable:active {
        cursor: grabbing;
        transform: scale(0.95);
        opacity: 0.9;
    }

    .drop-zone[data-user-answer]:not([data-user-answer=""]) {
        background-color: #d1e7dd;
        border-color: #0f5132;
        color: #0f5132;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const draggables = document.querySelectorAll('.draggable');
        const dropZones = document.querySelectorAll('.drop-zone');
        const answerInput = document.getElementById('dragAndDropAnswers');

        draggables.forEach(draggable => {
            draggable.addEventListener('dragstart', e => {
                e.dataTransfer.setData('text/plain', draggable.getAttribute('data-value'));
                e.dataTransfer.effectAllowed = 'move';
            });
        });

        dropZones.forEach(zone => {
            zone.addEventListener('dragover', e => {
                e.preventDefault();
                zone.style.border = '2px dashed #007bff';
            });

            zone.addEventListener('dragleave', e => {
                zone.style.border = 'none';
            });

            zone.addEventListener('drop', e => {
                e.preventDefault();
                zone.style.border = 'none';
                const value = e.dataTransfer.getData('text/plain');
                zone.textContent = value;
                zone.setAttribute('data-user-answer', value);

                // Update hidden input with user answers
                const answers = Array.from(dropZones).map(z => ({
                    zone: z.getAttribute('data-zone'),
                    answer: z.getAttribute('data-user-answer')
                }));
                answerInput.value = JSON.stringify(answers);
            });
        });

        // Form validation before submission
        document.getElementById('questionForm').addEventListener('submit', function(e) {
            const dragAndDropAnswers = document.getElementById('dragAndDropAnswers').value;
            const parsed = JSON.parse(dragAndDropAnswers);
            const isComplete = parsed.every(z => z.answer && z.answer.trim() !== '');

            if (!isComplete) {
                e.preventDefault();
                alert("Harap isi semua zona jawaban!");
            }
        });
    });
</script>