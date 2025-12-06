@php
    $product_images = $product->getMedia('products-media');
@endphp

<div class="col-lg-3 col-md-3">
    <ul class="nav nav-tabs" role="tablist">
        @foreach($product_images as $index => $media)
            @php
                $tabId = 'tabs-' . ($index + 1);
                $isVideo = Str::contains($media->mime_type, 'video');
            @endphp
            <li class="nav-item">
                <a class="nav-link {{ $index === 0 ? 'active' : '' }}" data-toggle="tab" href="#{{ $tabId }}" role="tab">
                    <div class="product-thumb-pic set-bg" data-setbg="{{ $media->getUrl() }}">
                        @if($isVideo)
                            <i class="fa fa-play"></i>
                        @endif
                    </div>
                </a>
            </li>
        @endforeach
    </ul>
</div>

<div class="col-lg-6 col-md-9">
    <div class="tab-content">
        @foreach($product_images as $index => $media)
            @php
                $tabId = 'tabs-' . ($index + 1);
                $isVideo = Str::contains($media->mime_type, 'video');
            @endphp
            <div class="tab-pane {{ $index === 0 ? 'active' : '' }}" id="{{ $tabId }}" role="tabpanel">
                <div class="product-details-pic-item">
                    @if($isVideo)
                        <img src="{{ $media->getUrl() }}" alt="{{ $product->name }}">
                        <a href="{{ $media->getUrl() }}" class="video-popup">
                            <i class="fa fa-play"></i>
                        </a>
                    @else
                        <img src="{{ $media->getUrl() }}" alt="{{ $product->name }}">
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
