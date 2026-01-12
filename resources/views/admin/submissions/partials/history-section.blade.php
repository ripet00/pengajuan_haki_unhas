<!-- Riwayat Review -->
@if($submission->histories && $submission->histories->count() > 0)
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mt-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
        <i class="fas fa-history mr-2 text-red-600"></i>Riwayat Review
    </h3>
    
    <div class="space-y-4">
        @foreach($submission->histories as $history)
        <div class="flex items-start space-x-4 pb-4 {{ !$loop->last ? 'border-b border-gray-200' : '' }}">
            <!-- Icon -->
            <div class="flex-shrink-0">
                @if($history->action === 'approved')
                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                        <i class="fas fa-check text-green-600"></i>
                    </div>
                @else
                    <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                        <i class="fas fa-times text-red-600"></i>
                    </div>
                @endif
            </div>
            
            <!-- Content -->
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between mb-1">
                    <p class="text-sm font-medium text-gray-900">
                        {{ $history->admin->name ?? 'Admin' }}
                    </p>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $history->action === 'approved' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $history->action === 'approved' ? 'Disetujui' : 'Ditolak' }}
                    </span>
                </div>
                <p class="text-xs text-gray-500 mb-2">
                    {{ $history->created_at->format('d F Y, H:i') }} WITA
                </p>
                @if($history->notes)
                    <div class="mt-2 bg-gray-50 rounded-lg p-3">
                        <p class="text-xs font-medium text-gray-700 mb-1">Catatan:</p>
                        <p class="text-sm text-gray-900 whitespace-pre-line">{{ $history->notes }}</p>
                    </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif
