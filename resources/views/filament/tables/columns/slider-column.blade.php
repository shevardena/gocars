@php
    use Illuminate\Support\Str;
    $images = $getRecord()->getMedia($column->getCollection());
    $sliderId = 'swiper-' . Str::random(10);
@endphp

@if ($images->isNotEmpty())
    <div style="position: relative; width: 180px; height: 160px; flex-shrink: 0; border-radius: 8px; overflow: hidden; background-color: #f9fafb; isolation: isolate;">
        <div class="swiper {{ $sliderId }}-swiper" style="width: 100%; height: 100%;">
            <div class="swiper-wrapper">
                @foreach($images as $image)
                    <div class="swiper-slide">
                        <a href="{{ $image->getFullUrl() }}" target="_blank" rel="noopener noreferrer">
                            <img
                                src="{{ $image->getFullUrl() }}"
                                style="width: 100%; height: 100%; object-fit: cover; object-position: center; cursor: pointer;"
                                alt="Car image"
                                loading="lazy"
                            >
                        </a>
                    </div>
                @endforeach
            </div>

            @if($images->count() > 1)
                <div class="swiper-button-next {{ $sliderId }}-next" style="right: 4px; width: 32px; height: 32px; background: rgba(255,255,255,0.8); backdrop-filter: blur(4px); border-radius: 50%; color: #374151; box-shadow: 0 1px 3px 0 rgba(0,0,0,0.1);"></div>
                <div class="swiper-button-prev {{ $sliderId }}-prev" style="left: 4px; width: 32px; height: 32px; background: rgba(255,255,255,0.8); backdrop-filter: blur(4px); border-radius: 50%; color: #374151; box-shadow: 0 1px 3px 0 rgba(0,0,0,0.1);"></div>
            @endif
        </div>
    </div>

    @once
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
        <style>
            .{{ $sliderId }}-next:after,
            .{{ $sliderId }}-prev:after {
                font-size: 14px !important;
                font-weight: bold !important;
                color: #374151 !important;
            }

            .{{ $sliderId }}-pagination .swiper-pagination-bullet {
                width: 8px !important;
                height: 8px !important;
                background: white !important;
                opacity: 0.6 !important;
            }

            .{{ $sliderId }}-pagination .swiper-pagination-bullet-active {
                opacity: 1 !important;
            }
        </style>
    @endonce


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Wait a bit for DOM to be fully ready
            setTimeout(function() {
                const sliderElement = document.querySelector('.{{ $sliderId }}-swiper');

                if (!sliderElement) {
                    console.log('Slider element not found');
                    return;
                }

                if (typeof Swiper === 'undefined') {
                    console.log('Swiper not loaded');
                    return;
                }

                // Check if already initialized
                if (sliderElement.swiper) {
                    sliderElement.swiper.destroy(true, true);
                }

                console.log('Initializing Swiper for', '.{{ $sliderId }}-swiper');

                const swiper = new Swiper(sliderElement, {
                    loop: {{ $images->count() > 1 ? 'true' : 'false' }},
                    pagination: {
                        el: '.{{ $sliderId }}-pagination',
                        clickable: true,
                    },
                    navigation: {
                        nextEl: '.{{ $sliderId }}-next',
                        prevEl: '.{{ $sliderId }}-prev',
                    },
                    // Add these for better initialization
                    observer: true,
                    observeParents: true,
                    observeSlideChildren: true,
                });

                console.log('Swiper initialized:', swiper);

            }, 300);
        });
    </script>
@else
    <div style="width: 100%; height: 160px; background-color: #f3f4f6; display: flex; align-items: center; justify-content: center; font-size: 14px; color: #9ca3af; border-radius: 8px;">
        No images
    </div>
@endif
