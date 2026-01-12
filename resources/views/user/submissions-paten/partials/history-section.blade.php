<!-- Riwayat Review -->
@if($submissionPaten->histories && $submissionPaten->histories->count() > 0)
<div class="bg-white rounded-lg shadow-md overflow-hidden mt-6">
    <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
        <h3 class="text-lg font-semibold text-white flex items-center">
            <i class="fas fa-history mr-2"></i>Riwayat Review
        </h3>
    </div>
    
    <div class="p-6">
        <div class="space-y-4">
            @foreach($submissionPaten->histories as $history)
            <div class="flex items-start space-x-4 pb-4 {{ !$loop->last ? 'border-b border-gray-200' : '' }}">
                <!-- Icon -->
                <div class="flex-shrink-0">
                    @if($history->action === 'approved')
                        <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                        </div>
                    @else
                        <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                            <i class="fas fa-times-circle text-red-600 text-xl"></i>
                        </div>
                    @endif
                </div>
                
                <!-- Content -->
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between mb-2">
                        <div>
                            <p class="text-sm font-semibold text-gray-900">
                                {{ $history->admin->name ?? 'Admin' }}
                            </p>
                            <div class="mt-1">
                                @if($history->review_type === 'format_review')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-file-alt mr-1"></i>Review Format
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        <i class="fas fa-microscope mr-1"></i>Review Substansi
                                    </span>
                                @endif
                            </div>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $history->action === 'approved' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            <i class="fas {{ $history->action === 'approved' ? 'fa-check' : 'fa-times' }} mr-1"></i>
                            {{ $history->action === 'approved' ? 'Disetujui' : 'Ditolak' }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-500 mb-2">
                        <i class="far fa-clock mr-1"></i>{{ $history->created_at->format('d F Y, H:i') }} WITA
                    </p>
                    @if($history->notes)
                        <div class="mt-3 bg-gray-50 rounded-lg p-4 border-l-4 {{ $history->action === 'approved' ? 'border-green-500' : 'border-red-500' }}">
                            <p class="text-xs font-semibold text-gray-700 mb-1 flex items-center">
                                <i class="fas fa-comment-alt mr-1"></i>Catatan Review:
                            </p>
                            <p class="text-sm text-gray-900 whitespace-pre-line">{{ $history->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        
        @if($submissionPaten->histories->count() > 1)
        <div class="mt-4 pt-4 border-t border-gray-200">
            <p class="text-xs text-gray-500 text-center">
                <i class="fas fa-info-circle mr-1"></i>
                Total {{ $submissionPaten->histories->count() }} kali review dilakukan
            </p>
        </div>
        @endif
    </div>
</div>
@endif
