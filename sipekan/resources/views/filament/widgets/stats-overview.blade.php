<div class="grid gap-6 grid-cols-1 md:grid-cols-2 lg:grid-cols-4">
    @foreach ($this->getStats() as $stat)
        <div class="stat-card stat-{{ $stat->color }} bg-white rounded-lg shadow p-6 border-l-4" 
             style="border-left-color: {{ $this->getStatColor($stat->color) }}">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    @if ($stat->label)
                        <p class="text-sm font-medium text-gray-600 mb-2">
                            {{ $stat->label }}
                        </p>
                    @endif
                    
                    @if ($stat->value)
                        <p class="text-3xl font-bold mb-4" style="color: {{ $this->getStatColor($stat->color) }}">
                            {{ $stat->value }}
                        </p>
                    @endif
                </div>
                
                @if ($stat->descriptionIcon)
                    <div class="text-2xl" style="color: {{ $this->getStatColor($stat->color) }}">
                        {!! $stat->descriptionIcon !!}
                    </div>
                @endif
            </div>
            
            @if ($stat->description)
                <p class="text-xs text-gray-500 border-t pt-3 mt-3">
                    {{ $stat->description }}
                </p>
            @endif
        </div>
    @endforeach
</div>
