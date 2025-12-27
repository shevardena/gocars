@php
    use Illuminate\Support\Str;

    $images = $record->getMedia('car_images');
    $sliderId = 'swiper_' . Str::random(10); // 🔥 underscore, not dash
@endphp

@if ($images->isNotEmpty())
    <div class="w-full">
        <div
            class="swiper"
            data-swiper-id="{{ $sliderId }}"
        >
            <div class="swiper-wrapper">
                @foreach($images as $image)
                    <div class="swiper-slide">
                        <img
                            src="{{ $image->getFullUrl() }}"
                            class="swiper-img"
                            draggable="false"
                        >
                    </div>
                @endforeach
            </div>

            @if($images->count() > 1)
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            @endif
        </div>
    </div>
@endif

<style>
    .swiper {
        width: 100%;
        height: 500px;
    }

    .swiper-wrapper {
        height: 100%;
    }

    .swiper-slide {
        width: 100%;
        height: 100%;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f3f4f6;
    }

    .swiper-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>

<script>
    function initCarImageSwipers() {
        if (typeof Swiper === 'undefined') return;

        document.querySelectorAll('.swiper[data-swiper-id]').forEach((el) => {
            if (el.swiper) {
                el.swiper.destroy(true, true);
            }

            const swiper = new Swiper(el, {
                loop: false,
                slidesPerView: 1,
                navigation: {
                    nextEl: el.querySelector('.swiper-button-next'),
                    prevEl: el.querySelector('.swiper-button-prev'),
                },
                observer: true,
                observeParents: true,
            });

            swiper.navigation.init();
            swiper.navigation.update();
        });
    }

    document.addEventListener('DOMContentLoaded', initCarImageSwipers);
    document.addEventListener('livewire:load', initCarImageSwipers);
    document.addEventListener('livewire:navigated', initCarImageSwipers);
</script>
