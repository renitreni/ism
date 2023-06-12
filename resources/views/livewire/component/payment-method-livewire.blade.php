<div class="col-md-12 mt-3 row">
    <div class="col-md-12">
        <h3>Payment Method</h3>
        <hr>
    </div>
    <div class="col-md-2">
        <div class="input-group ">
            <input type="text" class="form-control" placeholder="Enter Keyword" wire:model='payment'>
            <span class="input-group-btn">
                <button class="btn btn-success" type="button" wire:click='store'>
                    <i class="fa fa-plus-circle"></i>
                </button>
            </span>
        </div><!-- /input-group -->
        @error('payment')
            <span class="help-block text-danger">{{ $message }}</span>
        @enderror
    </div>
    @foreach ($paymentList as $item)
    <div class="col-md-1">
        <div class="row border p-2" style="width: auto">
            <div>
                <button type="button" class="btn btn-sm btn-danger" wire:click='destroy({{ $item['id'] }})'>
                    <i class="fa fa-trash"></i>
                </button>
            </div>
            <div class="ml-3 text-center">
                <label class="mt-1 mb-0">{{  $item['name']  }}</label>
            </div>
        </div>
    </div>
    @endforeach
</div>
