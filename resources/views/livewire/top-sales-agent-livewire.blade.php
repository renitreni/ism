<div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label>Month</label>
                            <input type="text" wire:model="month" class="form-control">
                            <select wire:model="month" id="">
                                
                            </select>
                        </div>
                        <div class="col-6 mb-3">
                            <label>Year</label>
                            <input type="number" wire:model="year" class="form-control">
                        </div>
                        <div class="col-12">
                            <div class="row">
                                <div class="col-6 mb-2">
                                    Sales Count
                                </div>
                                <div class="col-6 mb-2">
                                    Name
                                </div>
                                @foreach ($tops as $value)
                                    <div class="col-6">
                                        <strong>
                                            {{ $value['sales'] }}
                                        </strong>
                                    </div>
                                    <div class="col-6">
                                        <strong>
                                            {{ $value['name'] }}
                                        </strong>
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
