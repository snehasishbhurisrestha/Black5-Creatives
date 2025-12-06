@if (!empty($subbrands))
    @foreach ($subbrands as $subbrand)
        @if ($subbrand->parent_id == $parent_id) 
            <div class="form-check" style="margin-left: {{ $margin ? $margin : 20}}px;"> 
                <input class="form-check-input brand-checkbox" type="checkbox" name="brands[]"
                    value="{{ $subbrand->id }}" id="brand{{ $subbrand->id }}"
                    {{ in_array($subbrand->id, $selectedBrands) ? 'checked' : '' }}>
                <label class="form-check-label" for="brand{{ $subbrand->id }}">
                    {{ $subbrand->name }}
                </label>
            </div>
            {{-- @include('admin.products.child_sub_brand', [
                'subbrands' => $subbrand->children, 
                'parent_id' => $subbrand->id, 
                'selectedBrands' => $selectedBrands, 
                'margin' => 40
            ]) <!-- Recursive call --> --}}
        @endif
    @endforeach
@endif