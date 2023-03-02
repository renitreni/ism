<div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <label>Month</label>
                            <input type="text" wire:model="month" class="form-control">
                        </div>
                        <div class="col-6">
                            <label>Year</label>
                            <input type="number" wire:model="year" class="form-control">
                        </div>
                        <div class="col-12">
                            <div class="row">
                                <div class="col-6">
                                    Sales Count
                                </div>
                                <div class="col-6">
                                    Name
                                </div>
                                @foreach ($tops as $value)
                                    <div class="col-6">
                                        {{ $value['sales'] }}
                                    </div>
                                    <div class="col-6">
                                        {{ $value['name'] }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
