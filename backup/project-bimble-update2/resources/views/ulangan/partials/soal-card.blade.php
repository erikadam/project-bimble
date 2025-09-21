{{-- resources/views/ulangan/partials/soal-card.blade.php --}}
<div class="soal-card flex items-start justify-between p-3 bg-gray-100 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
    <div class="flex-1 mr-4">
        <div class="prose prose-sm dark:prose-invert max-w-none">
            {!! $soal->pertanyaan !!}
        </div>
    </div>

    <div class="flex-shrink-0">
        @if($action == 'add')
            <button
                type="button"
                data-soal-id="{{ $soal->id }}"
                class="add-soal-btn text-xs bg-blue-500 hover:bg-blue-600 text-white font-bold py-1 px-3 rounded transition-colors duration-200">
                Tambah
            </button>
        @else
            <button
                type="button"
                data-soal-id="{{ $soal->id }}"
                class="remove-soal-btn text-xs bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-3 rounded transition-colors duration-200">
                Hapus
            </button>
        @endif
    </div>
</div>
